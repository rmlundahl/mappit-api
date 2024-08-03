<?php declare(strict_types = 1);

namespace App\Services\Item;

use App\Models\Group;
use App\Models\User;
use App\Services\User\GetUser;

use \Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Auth, DB, Log;

class GetItem {
    
    /**
     * @var array<string, int|string>
     */
    private $data;

    private GetUser $getUser;

    /**
     * @param  array<string, int|string>  $parameterData
     */
    public function __construct(array $parameterData)
    {
        $this->data = $parameterData;
        
        if( empty($this->data['language']) ) {
            $this->data['language'] = App::getLocale();
        }
    }

    /**
     * Select all published markers on the map
     * 
     * @return \Illuminate\Support\Collection<int, \App\Models\Item>.
     */
    public function all_markers(): \Illuminate\Support\Collection
    {
        // only published items of item_type 10 (default) are markers on the map
        $this->data = array_merge($this->data, ['items.item_type_id'=>10, 'items.status_id'=>20]);

        // items.item_type_id in parameters?
        if( isset($this->data['items_item_type_id']) ) {
            // revert the _ back to . 
            $this->data['items.item_type_id'] = $this->data['items_item_type_id'];
            unset($this->data['items_item_type_id']);
        }

        return $this->all_with_default_nl();
    }   

    /**
     * @return null|\Illuminate\Support\Collection<int, \App\Models\Item>.
     */    
    public function all_from_user(): null|\Illuminate\Support\Collection
    {
        $user = Auth::user();

        if( !$user instanceof User) {
           return null;
        }

        // based on role and is_group_admin, a user can see items:
        // - author: can see own items, with status_id: 10, 20
        // - author && is_group_admin: can see all items in same group, with status_id: 10, 20
        // - editor: can see all items in same group and group descendants, with status_id: 10, 20, 99
        // - administrator: can see all items in same group and group descendants, all statuses

        if($user->role=='author' && $user->is_group_admin==0) {

            $this->data = array_merge($this->data, ['user_id'=>$user->id, 'status_id'=>'10,20']);

        } else if($user->role=='author' && $user->is_group_admin==1) {
            
            // Get all users from same group
            $this->getUser = new GetUser($user);
            $users_from_group = $this->getUser->users_from_group($user->group_id);
            if (empty($users_from_group)) {
                return null;
            }
            $user_ids = implode(',', $users_from_group->pluck('id')->all());
           
            $this->data = array_merge($this->data, ['user_id'=>$user_ids, 'status_id'=>'10,20']);

        } else if($user->role=='editor') {
            
            // Get all groups from user
            $groups = Group::find($user->group_id)->descendantsAndSelf()->get();
            if ($groups->isEmpty()) {
                return null;
            }
            $group_ids = $groups->pluck('id');
            
            $this->getUser = new GetUser($user);
            $users_from_groups = $this->getUser->users_from_groups($group_ids);
            if (empty($users_from_groups)) {
                return null;
            }
            $user_ids = implode(',', $users_from_groups->pluck('id')->all());
           
            $this->data = array_merge($this->data, ['user_id'=>$user_ids, 'status_id'=>'10,20,99']);

        } else if($user->role=='administrator') {
            
            // Get all groups from user
            $groups = Group::find($user->group_id)->descendantsAndSelf()->get();
            if ($groups->isEmpty()) {
                return null;
            }
            $group_ids = $groups->pluck('id');
            
            $this->getUser = new GetUser($user);
            $users_from_groups = $this->getUser->users_from_groups($group_ids);
            if (empty($users_from_groups)) {
                return null;
            }
            $user_ids = implode(',', $users_from_groups->pluck('id')->all());
           
            $this->data = array_merge($this->data, ['user_id'=>$user_ids]);

        } else {
            // no role found
            return null;
        }

        return $this->all();
    }

    /**
     *  @return \Illuminate\Support\Collection<int, \App\Models\Item>.
     */  
    public function all(): \Illuminate\Support\Collection
    {
        // select with item_properties
        $query = DB::table('items');

        // any parameters to add to the query?
        if( !empty($this->data) ) {
            foreach($this->data as $k => $v) {
                $v = (string) $v;
                if(strpos($v, ',')!==false) {
                    $array = explode(',', $v);
                    $query->whereIn($k, $array);
                } else {
                    $query->where($k, $v);
                }
            }
        }

        $items = $query->get();
        
        $items->transform( function ($item) {
            // add flattened properties
            if( !empty($item->item_properties)) {
                $i=0;
                foreach($item->item_properties as $r) {
                    ($item->item_properties)->put($r->key,$r->value);
                    unset($item->item_properties[$i]);
                    $i++;
                }
            }
            // add group_id 
            $item->group_id = $item->user->group_id ?? null;
            return $item;
        });

        return $items;
    }

    /**
     * @return \Illuminate\Support\Collection<int, \App\Models\Item>
     */  
    public function all_with_default_nl(): \Illuminate\Support\Collection
    {
        // look for items in the requested language, and use 'nl' records as fallback
        $preferred_language = $this->data['language'];

        $query = DB::table('items')
                    ->select('items.*', 'users.group_id')
                    ->join('users', 'items.user_id', '=', 'users.id')
                    ->leftJoin('items as i2', function($join) use ($preferred_language) {
                        $join->on('i2.id', '=', 'items.id')
                                ->where('i2.language', '=', $preferred_language)
                                ->where('items.language', '<>', $preferred_language);
                    })
                    ->whereIn('items.language', [$preferred_language, 'nl'])
                    ->whereNull('i2.id');

        // any parameters to add to the query?        
        foreach($this->data as $k => $v) {
            
            if($k==='language') continue;
            
            $v = (string) $v;
            
            if(strpos($v, ',')!==false) {
                $array = explode(',', $v);
                $query->whereIn($k, $array);
            } else {
                $query->where($k, $v);
            }
        }
        $items = $query->get();

        // look for item_properties in the requested language, and use 'nl' records as fallback
        $query = DB::table('item_properties')
                    ->select('item_properties.*')
                    ->join('items', 'item_properties.item_id', '=', 'items.id')
                    ->leftJoin('item_properties as p2', function($join) use ($preferred_language) {
                        $join->on('p2.id', '=', 'item_properties.id')
                                ->where('p2.language', '=', $preferred_language)
                                ->where('item_properties.language', '<>', $preferred_language);
                    
                    })
                    ->whereIn('item_properties.language', [$preferred_language, 'nl'])                        
                    ->whereNull('p2.id');
                    
        $item_properties = $query->get();

        // add flattened properties
        $items->transform( function ($item) use ($item_properties) {
            $item->item_properties = (array)[];
            
            foreach($item_properties as $r) {
                if($r->item_id===$item->id) {
                    // if the key already exists, create an array                    
                    if(isset($item->item_properties[$r->key])) {
                        if(!is_array($item->item_properties[$r->key])) {
                            // move existing value as first item in new array
                            $item->item_properties[$r->key] = [$item->item_properties[$r->key]];
                        }
                        $item->item_properties[$r->key][] = $r->value;
                    } else {
                        $item->item_properties[$r->key] = $r->value;
                    }
                }
            }
            
            return $item;
        });

        return $items;
        
    }
}

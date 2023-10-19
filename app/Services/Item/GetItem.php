<?php

namespace App\Services\Item;

use App\Models\Item;
use App\Models\Group;
use App\Services\User\GetUser;

use App, Auth, Storage;

class GetItem {
    
    private $data;
    private $getUser;

    public function __construct(array $parameterData)
    {
        $this->data = $parameterData;
        
        if( empty($this->data['language']) ) {
            $this->data['language'] = App::getLocale();
        }
    }

    public function all_markers()
    {
        // only published items of item_type 10 are markers on the map
        $this->data = array_merge($this->data, ['item_type_id'=>10, 'status_id'=>20]);    
        return $this->all();
    }   
        
    public function all_from_user()
    {
        $user = Auth::user();        

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
                return;
            }
            $user_ids = implode(',', $users_from_group->pluck('id')->all());
           
            $this->data = array_merge($this->data, ['user_id'=>$user_ids, 'status_id'=>'10,20']);

        } else if($user->role=='editor') {
            
            // Get all groups from user
            $groups = Group::find($user->group_id)->descendantsAndSelf()->get();
            if (empty($groups)) {
                return;
            }
            $group_ids = $groups->pluck('id');
            
            $this->getUser = new GetUser($user);
            $users_from_groups = $this->getUser->users_from_groups($group_ids);
            if (empty($users_from_groups)) {
                return;
            }
            $user_ids = implode(',', $users_from_groups->pluck('id')->all());
           
            $this->data = array_merge($this->data, ['user_id'=>$user_ids, 'status_id'=>'10,20,99']);

        } else if($user->role=='administrator') {
            
            // Get all groups from user
            $groups = Group::find($user->group_id)->descendantsAndSelf()->get();
            if (empty($groups)) {
                return;
            }
            $group_ids = $groups->pluck('id');
            
            $this->getUser = new GetUser($user);
            $users_from_groups = $this->getUser->users_from_groups($group_ids);
            if (empty($users_from_groups)) {
                return;
            }
            $user_ids = implode(',', $users_from_groups->pluck('id')->all());
           
            $this->data = array_merge($this->data, ['user_id'=>$user_ids]);

        } else {
            // no role found
            return;
        }

        return $this->all();
    }

    public function all()
    {
        // select with item_properties
        $query = Item::with('item_properties');

        // any parameters to add to the query?
        if( !empty($this->data) ) {
            foreach($this->data as $k => $v) {
                
                if(strpos($v, ',')!==false) {
                    $array = explode(',', $v);
                    $query->whereIn($k, $array);
                } else {
                    $query->where($k, $v);
                }
            }
        }

        $items = $query->get();
        
        // add flattened properties
        $items->map( function ($item) {
            if( !empty($item->item_properties)) {
                foreach($item->item_properties as $r) {
                    ($item->item_properties)->put($r->key,$r->value);
                }
            }
            // add featured image
            $path = '/items/'.$item->id.'/uitgelichte_afbeelding/';
            $files = Storage::disk('public')->allFiles($path);
            if( !empty($files[0]) ) {
                ($item->item_properties)->put('uitgelichte_afbeelding', $files[0]);
            }
        });

        return $items;
    }
}

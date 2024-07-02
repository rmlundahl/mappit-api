<?php declare(strict_types = 1);

namespace App\Services\User;

use App\Models\User;
use App\Models\Group;

use \Illuminate\Support\Collection;

use Auth;

class GetUser {
    
    private User $user;

    /**
     * @var array<string, string>
     */
    private $data;

    /**
     * @param  array<string, string>  $parameterData
     */
    public function __construct(User $user, array $parameterData=[])
    {
        $this->user = $user;
        $this->data = $parameterData;
    }

    /**
     * @return null|Collection<int, \App\Models\User>.
     */
    public function all(): null|Collection
    {
        $user = Auth::user();

        if( !$user instanceof User) {
            return null;
        }

        // based on role, a user can see users:
        // - author && is_group_admin: can see all users in same group, with status_id: 10, 20, 99
        // - editor: can see all users in same group and group descendants, with status_id: 10, 20, 99
        // - administrator: can see all users in same group and group descendants, all statuses
        
        if($user->role=='author' && $user->is_group_admin) {
           
            $users_from_group = $this->users_from_group($user->group_id);
            if ( !count($users_from_group) ) {
                return null;
            }
            $user_ids = implode(',', $users_from_group->pluck('id')->all());
            $this->data = array_merge($this->data, ['users.id'=>$user_ids, 'users.status_id'=>'10,20,99']);

        } else if($user->role=='editor') {
            
            $user_ids = $this->_get_user_ids();            
            $this->data = array_merge($this->data, ['users.id'=>$user_ids, 'users.status_id'=>'10,20,99']);

        } else if($user->role=='administrator') {
            
            $user_ids = $this->_get_user_ids();
            $this->data = array_merge($this->data, ['users.id'=>$user_ids]);

        } else {
            // no role found
            return null;
        }

        $query = $this->user
            ->select('users.*','groups.id as group_id','groups.name as group_name')
            ->join('groups', 'users.group_id', '=', 'groups.id');
        
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

        $users = $query->get();

        return $users;
    }

    private function _get_user_ids(): null|string
    {
        $user = Auth::user();
        
        if( !$user instanceof User) {
            return null;
        }

        // Get all groups from user
        $groups = Group::find($user->group_id)->descendantsAndSelf()->get();
        if ($groups->isEmpty()) {
            return null;
        }
        $group_ids = $groups->pluck('id');
               
        $users_from_groups = $this->users_from_groups($group_ids);
        if ( !count($users_from_groups) ) {
            return null;
        }
        $user_ids = implode(',', $users_from_groups->pluck('id')->all());

        return $user_ids;
    }

    /**
     * @return null|Collection<int, \App\Models\User>.
     */
    public function users_from_group(int $group_id=null): null|Collection
    {
        if( !$group_id ) return null;

        return $this->user->where('group_id', $group_id)->get();
    }

    /**
     * @param  Collection<int, \App\Models\User>  $group_ids
     * 
     * @return null|Collection<int, \App\Models\User>.
     */
    public function users_from_groups(Collection $group_ids): null|Collection
    {
        if( !count($group_ids) ) return null;

        return $this->user->whereIn('group_id', $group_ids)->get();
    }
}

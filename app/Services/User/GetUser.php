<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\Group;

use Auth;

class GetUser {
    
    private $user;
    private $data;

    public function __construct(User $user, array $parameterData=[])
    {
        $this->user = $user;
        $this->data = $parameterData;
    }

    public function all()
    {
        $user = Auth::user();

        // based on role, a user can see users:
        // - author && is_group_admin: can see all users in same group, with status_id: 10, 20, 99
        // - editor: can see all users in same group and group descendants, with status_id: 10, 20, 99
        // - administrator: can see all users in same group and group descendants, all statuses
        
        if($user->role=='author' && $user->is_group_admin) {
           
            $users_from_group = $this->users_from_group($user->group_id);
            if (empty($users_from_group)) {
                return;
            }
            $user_ids = implode(',', $users_from_group->pluck('id')->all());
            $this->data = array_merge($this->data, ['id'=>$user_ids, 'status_id'=>'10,20,99']);

        } else if($user->role=='editor') {
            
            $user_ids = $this->_get_user_ids();            
            $this->data = array_merge($this->data, ['id'=>$user_ids, 'status_id'=>'10,20,99']);

        } else if($user->role=='administrator') {
            
            $user_ids = $this->_get_user_ids();
            $this->data = array_merge($this->data, ['user_id'=>$user_ids]);

        } else {
            // no role found
            return;
        }

        $query = $this->user->query();

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

    private function _get_user_ids()
    {
        $user = Auth::user();

        // Get all groups from user
        $groups = Group::find($user->group_id)->descendantsAndSelf()->get();
        if (empty($groups)) {
            return;
        }
        $group_ids = $groups->pluck('id');
               
        $users_from_groups = $this->users_from_groups($group_ids);
        if (empty($users_from_groups)) {
            return;
        }
        $user_ids = implode(',', $users_from_groups->pluck('id')->all());

        return $user_ids;
    }

    public function users_from_group($group_id=null)
    {
        if( !$group_id ) return;

        return $this->user->where('group_id', $group_id)->get();
    }

    public function users_from_groups($group_ids=[])
    {
        if( empty($group_ids) ) return;

        return $this->user->whereIn('group_id', $group_ids)->get();
    }
}

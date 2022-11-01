<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB, Log, Str;

class UpdateUser {
    
    private $data;
    private $user;

    public function __construct(array $updateUserData, User $user)
    {
        $this->data = $updateUserData;
        $this->user = $user;
    }

    public function update()
    {
        if( !empty($this->data['name']) )
            $this->user->name     = $this->data['name'];

        if( !empty($this->data['email']) )
            $this->user->email    = $this->data['email'];
        
        if( !empty($this->data['password']) && !empty($this->data['password_confirmation']) ) {
            
            if( $this->data['password']===$this->data['password_confirmation'] ) {
                $this->user->password = Hash::make($this->data['password']);
            }
        }

        if( !empty($this->data['group_id']) )
            $this->user->group_id = $this->data['group_id'];

        if( isset($this->data['is_group_admin']) )
            $this->user->is_group_admin = intval($this->data['is_group_admin']);

        if( !empty($this->data['role']) )
            $this->user->role = $this->data['role'];

        if( !empty($this->data['status_id']) )
            $this->user->status_id = $this->data['status_id'];
        
        $this->user->save();

        return $this->user;
    }

}

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
            Log::debug($this->data);
            if( $this->data['password']===$this->data['password_confirmation'] ) {
                $this->user->password = Hash::make($this->data['password']);
            }
        }

        if( !empty($this->data['status_id']) )
            $this->user->status_id = $this->data['status_id'];
        
        $this->user->save();

Log::debug($this->user);
        return $this->user;
    }

}

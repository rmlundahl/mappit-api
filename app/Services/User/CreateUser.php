<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App;

class CreateUser {
    
    private $data;

    public function __construct($newUserData)
    {
        $this->data = $newUserData;
    }

    public function save()
    {
        $user = new User;
        $user->name           = $this->data['name'];
        $user->email          = $this->data['email'];
        $user->password       = Hash::make($this->data['password']);
        $user->group_id       = $this->data['group_id'] ?? null;
        $user->is_group_admin = $this->data['is_group_admin'] ?? 0;
        $user->role           = $this->data['role'] ?? 'author';
        $user->locale         = $this->data['locale'] ?? App::getLocale();
        $user->status_id      = $this->data['status_id'] ?? 1;
        $user->save();

        if(!empty($this->data['user_preferences'])) {
            foreach($this->data['user_preferences'] as $k => $v) {
                $user->user_preferences()->create([
                    'key'=> $k,
                    'val'=> $v,
                ]);
            }
        }
        
        return $user;
    }
    
}

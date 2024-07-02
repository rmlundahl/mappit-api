<?php declare(strict_types = 1);

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App;

class CreateUser {
    
    /**
     * @var array<string, string>
     */
    private $data;

    /**
     * @param  array<string, string>  $newUserData
     */
    public function __construct($newUserData)
    {
        $this->data = $newUserData;
    }

    public function save(): User
    {
        $user = new User;
        $user->name           = $this->data['name'];
        $user->email          = $this->data['email'];
        $user->password       = Hash::make($this->data['password']);
        $user->group_id       = (int) ($this->data['group_id'] ?? 1);
        $user->is_group_admin = (bool) ($this->data['is_group_admin'] ?? 0);
        $user->role           = $this->data['role'] ?? 'author';
        $user->locale         = $this->data['locale'] ?? App::getLocale();
        $user->status_id      = (int) ($this->data['status_id'] ?? 1);
        $user->save();

        if(!empty($this->data['user_preferences'])) {
            foreach((array) $this->data['user_preferences'] as $k => $v) {
                $user->user_preferences()->create([
                    'key'=> $k,
                    'val'=> $v,
                ]);
            }
        }
        
        return $user;
    }
    
}

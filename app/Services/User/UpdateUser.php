<?php declare(strict_types = 1);

namespace App\Services\User;

use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Hash;
use DB, Log, Str;

class UpdateUser {
    
    /**
     * @var array<string, string>
     */    
    private array $data;
    private User $user;

    /**
     * @param  array<string, string>  $updateUserData
     */
    public function __construct(array $updateUserData, User $user)
    {
        $this->data = $updateUserData;
        $this->user = $user;
    }

    public function update(): User
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
            $this->user->group_id = (int) $this->data['group_id'];

        if( isset($this->data['is_group_admin']) )
            $this->user->is_group_admin = (bool) ($this->data['is_group_admin']);

        if( !empty($this->data['role']) )
            $this->user->role = $this->data['role'];

        if( !empty($this->data['locale']) )
            $this->user->locale = $this->data['locale'];

        if( !empty($this->data['status_id']) )
            $this->user->status_id = (int) $this->data['status_id'];
        
        $this->user->save();

        // update user preferences?
        if(!empty($this->data['user_preferences'])) {
            foreach((array) $this->data['user_preferences'] as $k => $v) {

                $userPreference = $this->user->user_preferences()->where('key', '=', $k)->first();
                
                if(empty($userPreference)) {
                    $userPreference = new UserPreference;
                }
                $userPreference->key = (string) $k;
                $userPreference->val = $v;
                $this->user->user_preferences()->save($userPreference);

            }
        }

        return $this->user;
    }

}

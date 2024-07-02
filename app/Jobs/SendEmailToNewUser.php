<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserPreference;
use App\Notifications\UserCreated;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Auth;

/**
 * 
 */
class SendEmailToNewUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private UserPreference $userPreference;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // test job on Queue: php artisan queue:work --queue=emails
        // restart after code change
         
        $password = Str::random(32);
        $this->user->password = Hash::make($password);
        $this->user->save();
       
        $this->user->notify(new UserCreated(
            $this->user->name, 
            $this->user->email, 
            $password,
            $this->_get_cc_email() )
        );
    }

    private function _get_cc_email(): string
    {
        $this->userPreference = UserPreference::where('user_id', $this->user->id)->where('key','notify_when_created_cc_email')->first();
        
        if( !empty($this->userPreference->val) ) {
            return $this->userPreference->val;
        }

        return config('mail.from.address');
    }
}

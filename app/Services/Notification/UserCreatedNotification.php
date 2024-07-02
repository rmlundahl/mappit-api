<?php

namespace App\Services\Notification;

use App\Jobs\SendEmailToNewUser;
use App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Queue;

class UserCreatedNotification {

    public function send(): void
    {
        /**
         * Select all users that:
         * - that have status 20
         * - and have 'notify_when_created' = true in their preferences
         */ 

        $users = User::where('status_id', '=', 20)
                    ->whereHas('user_preferences', function (Builder $query) {
                        $query->where('key', '=', 'notify_when_created')
                              ->where('val', '=', 'true');
                    })->get();                    
        
        $seconds = 0;

        foreach($users as $user) {
            Queue::laterOn('emails', $seconds, new SendEmailToNewUser($user));
            $seconds++;
        }

    }
}

<?php

namespace App\Services\Notification;

use App\Notifications\ItemPublished;
use App\Models\User;
use App\Models\Item;

use Illuminate\Database\Eloquent\Builder;

use Log, Notification;

class ItemPublishedNotification {

    private Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function sendNotifications(): void 
    {

        /**
         * Select all users that:
         * - are in the same group as the author
         * - are editor or administrator or group_admin
         * - and have 'notify_on_item_published' = true in their preferences
         */ 

        $users = User::where('group_id', $this->item->user?->group_id)
                    ->whereHas('user_preferences', function (Builder $query) {
                        $query->where('key', '=', 'notify_on_item_published')
                              ->where('val', '=', 'true');
                    })
                    ->where(function (Builder $query) {
                        $query->where('role', 'administrator')
                                     ->orWhere('role', 'editor')
                                     ->orWhere('is_group_admin', 1);
                    });
                    
                    // $sql = $users->toSql();
                    // Log::debug($sql);
                    // $bindings = $users->getBindings();
                    // Log::debug($bindings);
                    $users = $users->get();

        Notification::send($users, new ItemPublished($this->item));
    }
}

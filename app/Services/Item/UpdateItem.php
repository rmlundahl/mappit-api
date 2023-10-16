<?php

namespace App\Services\Item;

use App\Models\Item;
use App\Models\ItemProperty;
use App\Services\ItemProperty\SaveItemProperty;
use App\Services\ItemCollection\SaveItemCollection;
use App\Services\Notification\ItemPublishedNotification;

use DB, Log, Str;

class UpdateItem {
    
    private $data;
    private $item;

    public function __construct(array $updateItemData, Item $item)
    {
        $this->data = $updateItemData;
        $this->item = $item;
    }

    public function update()
    {
        if( !empty($this->data['item_type_id']) )
            $this->item->item_type_id = $this->data['item_type_id'];
        
        $this->item->external_id  = $this->data['external_id'] ?? null;
        
        if( !empty($this->data['name']) )
            $this->item->name     = $this->data['name'];

        if( !empty($this->data['slug']) ) {
            $this->item->slug     = Str::slug($this->data['slug']);
        } else {
            $this->item->slug     = Str::slug($this->data['name']);
        }
        
        $this->item->content      = $this->data['content'] ?? null;
        $this->item->user_id      = $this->data['user_id'] ?? 1;

        if( !empty($this->data['status_id']) ) {
            $this->_check_whether_notification_should_be_sent();
            $this->item->status_id = $this->data['status_id'];
        }
        
        $this->item->save();

        // save item properties
        $this->data['item_id'] = $this->item->id;
        $this->data['status_id'] = $this->item->status_id;
        
        $saveItemProperty = new SaveItemProperty($this->data);
        $saveItemProperty->save();
        
        // save collection
        if( isset($this->data['collection_items']) ) {
            
            $saveItemCollection = new SaveItemCollection($this->data);
            $saveItemCollection->save();
        }

        return $this->item;
    }

    // if the frontend indicated and status changed to 'published' (status_id = 20) we should send a notification
    private function _check_whether_notification_should_be_sent()
    {
        // the frontend should send a 'should_notify' flag
        if(empty($this->data['should_notify'])) return;

        if($this->data['status_id'] != 20) return;
        
        if($this->data['status_id'] != $this->item->status_id) {
            $notify = new ItemPublishedNotification($this->item);
            $notify->sendNotifications();
        }
    }
}

<?php declare(strict_types = 1);

namespace App\Services\Item;

use App\Models\Item;
use App\Services\ItemProperty\SaveItemProperty;
use App\Services\ItemCollection\SaveItemCollection;
use App\Services\Notification\ItemPublishedNotification;

use Str;

class UpdateItem {

    /**
     * @var array<string, int|string>
     */    
    private array $data;
    private Item $item;

    /**
     * @param  array<string, int|string>  $updateItemData
     */
    public function __construct(array $updateItemData, Item $item)
    {
        $this->data = $updateItemData;
        $this->item = $item;
    }

    public function update(): Item
    {
        if( !empty($this->data['item_type_id']) )
            $this->item->item_type_id = (int) $this->data['item_type_id'];
        
        if( !empty($this->data['external_id']) )
            $this->item->external_id = (string) $this->data['external_id'];

        if( !empty($this->data['name']) )
            $this->item->name = (string) $this->data['name'];

        if( !empty($this->data['slug']) ) {
            $this->item->slug = Str::slug((string) $this->data['slug']);
        } else {
            $this->item->slug = Str::slug((string) $this->data['name']);
        }
        
        if( !empty($this->data['content']) )
            $this->item->content = (string) $this->data['content'];

        $this->item->user_id = (int) ($this->data['user_id'] ?? 1);

        if( !empty($this->data['status_id']) ) {
            $this->_check_whether_notification_should_be_sent();
            $this->item->status_id = (int) $this->data['status_id'];
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
    private function _check_whether_notification_should_be_sent(): void
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

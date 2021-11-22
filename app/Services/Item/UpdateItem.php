<?php

namespace App\Services\Item;

use App\Models\Item;
use \Str;

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
            $this->item->name         = $this->data['name'];

        if( !empty($this->data['slug']) ) {
            $this->item->slug     = Str::slug($this->data['slug']);
        } else {
            $this->item->slug     = Str::slug($this->data['name']);
        }
        
        $this->item->content      = $this->data['content'] ?? null;
        $this->item->user_id      = $this->data['user_id'] ?? 1;
        $this->item->status_id    = $this->data['status_id'] ?? 1;
        
        $this->item->save();

        return $this->item;
    }
}

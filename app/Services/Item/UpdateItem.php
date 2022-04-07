<?php

namespace App\Services\Item;

use App\Models\Item;
use App\Models\ItemProperty;

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
        $this->item->status_id    = $this->data['status_id'] ?? 1;
        
        $this->item->save();

        $this->_save_item_properties();
        
        return $this->item;
    }

    private function _save_item_properties()
    {
        // abort if no data
        if (empty($this->data['item_properties'])) return;

        // delete exsiting data
        DB::table('item_properties')->where('item_id', $this->item->id)->delete();

        // insert new data
        $data = json_decode($this->data['item_properties'], true);
        
        $item_property = new ItemProperty;
        $item_property->language  = $this->item->language;
        $item_property->item_id   = $this->item->id;
        $item_property->status_id = $this->item->status_id;

        foreach ($data as $r) {
            foreach($r as $k => $v) {
                $item_property->key = $k;
                $item_property->value = $v;
                $item_property->save();
            }
        }
    }
}

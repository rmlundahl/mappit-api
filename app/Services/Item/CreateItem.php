<?php

namespace App\Services\Item;

use App\Models\Item;
use \Str;

class CreateItem {
    
    private $data;

    public function __construct($newItemData)
    {
        $this->data = $newItemData;
    }

    public function save()
    {
        $item = new Item;
        $item->language     = $this->data['language'];
        $item->item_type_id = $this->data['item_type_id'];
        $item->external_id  = $this->data['external_id'] ?? null;
        $item->name         = $this->data['name'];
        
        if( !empty($this->data['slug']) ) {
            $item->slug     = Str::slug($this->data['slug']);
        } else {
            $item->slug     = Str::slug($this->data['name']);
        }
        
        $item->content      = $this->data['content'] ?? null;
        $item->user_id      = $this->data['user_id'];
        $item->status_id    = $this->data['status_id'] ?? 1;
        
        if ( $item->save() ) {
            // Necessary because the save() method does not return the last created 'id' in the model
            $item = Item::orderBy('id','desc')->first();
        }

        return $item;
    }

    private function _create_unique_slug()
    {
        // check or
        $this->data['slug'];
        $query = "SELECT $field_name FROM $table_name WHERE $field_name = '".$slug."' OR $field_name LIKE '".$slug."-[0-9]*' ORDER BY LENGTH($field_name), $field_name DESC LIMIT 1";
        $query = "SELECT $field_name FROM $table_name WHERE $field_name = '".$slug."' OR $field_name REGEXP '".$slug."-[0-9]*' ORDER BY LENGTH($field_name) DESC, $field_name DESC LIMIT 1";
    }
}

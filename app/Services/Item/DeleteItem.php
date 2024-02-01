<?php

namespace App\Services\Item;

use App\Models\Item;


class DeleteItem {
    
    private $data;
    private $item;

    public function __construct(array $deleteItemData, Item $item)
    {
        $this->data = $deleteItemData;
        $this->item = $item;
    }


    public function delete()
    {
        $this->item->status_id = 99;
        
        return $this->item->save();

    }
}

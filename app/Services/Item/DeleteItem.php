<?php

namespace App\Services\Item;

use App\Models\Item;


class DeleteItem {
    
    private $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }


    public function delete()
    {
        $this->item->status_id = 99;
        
        return $this->item->save();

    }
}

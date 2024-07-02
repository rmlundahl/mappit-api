<?php declare(strict_types = 1);

namespace App\Services\Item;

use App\Models\Item;


class DeleteItem {
    
    private Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }


    public function delete(): bool
    {
        $this->item->status_id = 99;
        
        return $this->item->save();

    }
}

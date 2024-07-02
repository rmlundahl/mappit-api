<?php declare(strict_types = 1);

namespace App\Services\Item;

use App\Models\Item;
use App\Services\ItemProperty\SaveItemProperty;
use App\Services\ItemCollection\SaveItemCollection;

use Str;

class CreateItem {
    
    /**
     * @var array<string, string>
     */
    private $data;

    /**
     * @param  array<string, string>  $newItemData
     */
    public function __construct($newItemData)
    {
        $this->data = $newItemData;
    }

    public function save(): Item
    {
        $item = new Item;
        $item->language     = $this->data['language'];
        $item->item_type_id = (int) $this->data['item_type_id'];
        $item->external_id  = $this->data['external_id'] ?? null;
        $item->name         = $this->data['name'];
        
        if( !empty($this->data['slug']) ) {
            $item->slug     = Str::slug($this->data['slug']);
        } else {
            $item->slug     = Str::slug($this->data['name']);
        }
        
        $item->content      = $this->data['content'] ?? null;
        $item->user_id      = (int) $this->data['user_id'];
        $item->status_id    = $this->data['status_id'] ?? 1;
        
        if ( $item->save() ) {
            // Necessary because the save() method does not return the last created 'id' in the model
            $item = Item::orderBy('id','desc')->first();

            $this->data['item_id'] = $item->id;
            $saveItemProperty = new SaveItemProperty($this->data);
            $saveItemProperty->save();

            // save collection
            if( isset($this->data['collection_items']) ) {
                
                $saveItemCollection = new SaveItemCollection($this->data);
                $saveItemCollection->save();
            }
        }

        return $item;
    }

}

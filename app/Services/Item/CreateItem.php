<?php declare(strict_types = 1);

namespace App\Services\Item;

use App\Models\Item;
use App\Services\ItemProperty\SaveItemProperty;
use App\Services\ItemCollection\SaveItemCollection;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Str;

class CreateItem {
    
    /**
     * @var array<string, int|string>
     */
    private $data;

    /**
     * @param  array<string, int|string>  $newItemData
     */
    public function __construct($newItemData)
    {
        $this->data = $newItemData;
    }

    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function save(): Item
    {
        $item = new Item;
        $item->language     = (string) $this->data['language'];
        $item->item_type_id = (int) $this->data['item_type_id'];        
        if( !empty($this->data['external_id']) ) $item->external_id = (string) $this->data['external_id'];
        $item->name         = (string) $this->data['name'];
        
        if( !empty($this->data['slug']) ) {
            $item->slug     = Str::slug((string) $this->data['slug']);
        } else {
            $item->slug     = Str::slug((string) $this->data['name']);
        }

        if( !empty($this->data['content']) ) $item->content = (string) $this->data['content'];
        $item->user_id      = (int) $this->data['user_id'];
        $item->status_id    = (int) ($this->data['status_id'] ?? 1);
        
        if ( $item->save() ) {
            // Necessary because the save() method does not return the last created 'id' in the model
            $item = Item::orderBy('id','desc')->first();

            if(!$item) throw (new ModelNotFoundException);

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

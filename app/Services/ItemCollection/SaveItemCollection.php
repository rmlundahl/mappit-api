<?php

namespace App\Services\ItemCollection;

use App\Models\ItemCollection;

use DB, Exception, Log;

class SaveItemCollection {
    
    private $data;

    public function __construct($itemData)
    {
        $this->data = $itemData;
    }

    public function save()
    {
        
        try {
            // delete exsiting data
            DB::table('item_collection')->where('item_id', $this->data['item_id'])->delete();

            if(empty($this->data['collection_items'])) return;

            // insert new data
            $data = explode(',', $this->data['collection_items']);            

            foreach ($data as $v) {
                $item_collection = new ItemCollection;            
                $item_collection->item_id = $this->data['item_id'];
                $item_collection->collection_item_id = $v;
                $item_collection->save();
            }

        } catch (Exception $e) {
            Log::error('SaveItemCollection->save(): '.$e->getMessage());
        }
    }
   
}

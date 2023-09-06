<?php

namespace App\Services\ItemProperty;

use App\Models\ItemProperty;
use App\Services\Image\StoreImage;

use DB, Exception, Log;

class SaveItemProperty {
    
    private $data;

    public function __construct($itemData)
    {
        $this->data = $itemData;
    }

    public function save()
    {
        // abort if no data
        if (empty($this->data['item_properties'])) return;

        if ( empty($this->data['item_id']) || empty($this->data['language']) ) return;
        
        try {
            // delete exsiting data
            DB::table('item_properties')->where('item_id', $this->data['item_id'])->where('language', $this->data['language'])->delete();

            // insert new data
            $data = $this->data['item_properties'];
            
            if(empty($data)) return;

            foreach ($data as $k => $v) {
                               
                $item_property = new ItemProperty;
                $item_property->language  = $this->data['language'];
                $item_property->item_id   = $this->data['item_id'];
                $item_property->status_id = $this->data['status_id'];
                
                if( gettype($v)==='string' ) {

                    $item_property->key = $k;
                    $item_property->value = $v;
                    $item_property->save();

                } else if( gettype($v)==='object' ) {
                   
                    // Images are send as Object in a subdirectory
                    $clear_sub_directory = true;
                    $storeImage = new StoreImage( ['file'=>$v, 'item_id'=>$item_property->item_id], '/'.$k, $clear_sub_directory);
                    $storeImage = $storeImage->store();

                }
            }

        } catch (Exception $e) {
            
            Log::error('SaveItemProperty->save(): '.$e->getMessage());
        }
    }
   
}

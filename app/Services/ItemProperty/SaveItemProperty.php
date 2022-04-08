<?php

namespace App\Services\ItemProperty;

use App\Models\ItemProperty;

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
            DB::table('item_properties')->where('item_id', $this->data['item_id'])->delete();

            // insert new data
            $data = $this->data['item_properties'];
                       
            foreach ($data as $r) {
                               
                $item_property = new ItemProperty;
                $item_property->language  = $this->data['language'];
                $item_property->item_id   = $this->data['item_id'];
                $item_property->status_id = $this->data['status_id'];
                
                if( gettype($r)==='string' ) $r = json_decode($r, true);
                
                foreach($r as $k => $v) {
                    $item_property->key = $k;
                    $item_property->value = $v;
                    $item_property->save();
                    // Log::debug($item_property);
                }
            }

        } catch (Exception $e) {
            
            Log::error('SaveItemProperty->save(): '.$e->getMessage());
        }
    }
}

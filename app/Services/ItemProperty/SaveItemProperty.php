<?php

namespace App\Services\ItemProperty;

use App\Models\ItemProperty;
use App\Services\Image\StoreImage;

use DB, Exception, Log;

class SaveItemProperty {
    
    /**
     * @var array<string, int|string>
     */    
    private array $data;

    /**
     * @param  array<string, int|string>  $itemData
     */
    public function __construct(array $itemData)
    {
        $this->data = $itemData;
    }

    public function save(): void
    {
        // abort if no data
        if (empty($this->data['item_properties'])) return;

        if ( empty($this->data['item_id']) || empty($this->data['language']) ) return;

        DB::beginTransaction();
        try {
            // delete exsiting data
            DB::table('item_properties')->where('item_id', $this->data['item_id'])->where('language', $this->data['language'])->delete();

            // insert new data
            $data = (array) $this->data['item_properties'];
            
            foreach ($data as $k => $v) {
                
                $item_property = new ItemProperty;
                $item_property->language  = (string) $this->data['language'];
                $item_property->item_id   = (int) $this->data['item_id'];
                $item_property->status_id = (int) $this->data['status_id'];
                
                if( is_string($v) ) {

                    $item_property->key = (string) $k;
                    $item_property->value = $v;
                    $item_property->save();

                } else if( is_array($v) ) {

                    foreach($v as $r) {
                        
                        if(empty($r)) continue;

                        $item_property = new ItemProperty;
                        $item_property->language  = (string) $this->data['language'];
                        $item_property->item_id   = (int) $this->data['item_id'];
                        $item_property->status_id = (int) $this->data['status_id'];

                        $item_property->key = (string) $k;
                        $item_property->value = $r;
                        $item_property->save();
                    }

                } else if( is_object($v) ) {
                   
                    // Images are send as Object in a subdirectory
                    $clear_sub_directory = true;
                    $storeImage = new StoreImage( ['file'=>$v, 'item_id'=>$item_property->item_id], '/'.$k, $clear_sub_directory);
                    $filename = $storeImage->store();

                    $item_property->key = (string) $k;
                    $item_property->value = $filename;
                    $item_property->save();

                }
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('SaveItemProperty->save(): '.$e->getMessage());
        }
        DB::commit();
    }
   
}

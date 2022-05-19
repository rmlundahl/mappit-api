<?php

namespace App\Services\Item;

use App\Models\Item;

use App, Storage;

class GetItem {
    
    private $data;

    public function __construct(array $parameterData)
    {
        $this->data = $parameterData;
        
        if( empty($this->data['language']) ) {
            $this->data['language'] = App::getLocale();
        }
    }

    public function all_markers()
    {
        // only published items of item_type 10 are markers on the map
        $this->data = array_merge($this->data, ['item_type_id'=>10, 'status_id'=>20]);    
        return $this->all();
    }   
        

    public function all()
    {
        // select with item_properties
        $query = Item::with('item_properties');

        // any parameters to add to the query?
        if( !empty($this->data) ) {
            foreach($this->data as $k => $v) {
                $query->where($k, $v);
            }
        }

        $items = $query->get();

        // add flattened properties
        $items->map( function ($item) {
            if( !empty($item->item_properties)) {
                foreach($item->item_properties as $r) {
                    ($item->item_properties)->put($r->key,$r->value);
                }
            }
            // add featured image
            $path = '/items/'.$item->id.'/uitgelichte_afbeelding/';
            $files = Storage::allFiles($path);
            if( !empty($files[0]) ) {
                ($item->item_properties)->put('uitgelichte_afbeelding', $files[0]);
            }
        });

        return $items;
    }
}

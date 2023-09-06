<?php

namespace App\Services\Collection;

use App\Models\ItemCollection;
use App\Models\Item;

use App\Services\Item\GetItem;

use App, DB;

class GetCollection {
        
    private $data;

    public function __construct(array $parameterData=[])
    {
        $this->data = $parameterData;
        
        if( empty($this->data['language']) ) {
            $this->data['language'] = App::getLocale();
        }
    }

    public function all()
    {
        $all_collections = DB::table('items as collection')
            ->leftJoin('item_collection', 'collection.id', '=', 'item_collection.collection_item_id')
            ->leftJoin('items', 'item_collection.item_id', '=', 'items.id')
            ->where('collection.item_type_id', 30)
            ->where('collection.language', App::getLocale())
            ->where('items.language', App::getLocale())
            ->select(
                'collection.id as collection_id',
                'collection.name as collection_name',
                'collection.slug as collection_slug',
                'collection.content as collection_content',
                'collection.status_id as collection_status_id',
                'items.*'
            )
            ->orderBy('collection_id')
            ->get();
        
        if(count($all_collections)==0) return;

        // Get all items with flattened item_properties
        $getItem = new GetItem( ['language' => $this->data['language']] );
        $all_items = $getItem->all();

        // created a nested result
        $collections = [];
        foreach($all_collections as $r) {
            
            // create a new top-level
            if( !isset($collections[$r->collection_id]) ) {
                $collections[$r->collection_id] = ['id'=>$r->collection_id, 'name'=>$r->collection_name, 'slug'=>$r->collection_slug, 'content'=>$r->collection_content, 'status_id'=>$r->collection_status_id, 'elements'=>[]];
            }
            // add to items to elements
            if( !empty($r->id) ) {
                // find the item in $all_items, and add it
                $item_with_properties = $all_items->first(function($item) use ($r) {
                    return $item->id == $r->id;
                });
                
                $collections[$r->collection_id]['elements'][] = $item_with_properties;
            }
        }

        return $collections;
    }

}

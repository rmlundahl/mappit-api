<?php

namespace App\Services\Collection;

use App\Models\ItemCollection;
use App\Models\Item;

use App\Services\Item\GetItem;

use \Illuminate\Support\Collection;
use App, DB;

class GetCollection {
    
    /**
     * @var array<string, string>
     */
    private array $data;

    /**
     * @param  array<string, string>  $parameterData
     */
    public function __construct(array $parameterData=[])
    {
        $this->data = $parameterData;
        
        if( empty($this->data['language']) ) {
            $this->data['language'] = App::getLocale();
        }
    }

    /**
     * @return array<int, array<mixed>>
     */    
    public function all_from_user(): array
    {
        // A Collection is an Item of type_id = 30
        $getItem = new GetItem( ['item_type_id' => 30, 'language' => $this->data['language']] );
        $all_collections = $getItem->all_from_user();

        if(count($all_collections)==0) return [];        

        // Get all items with flattened item_properties
        $getItem = new GetItem( ['language' => $this->data['language']] );
        $all_items = $getItem->all();

        // Add items to the collection
        $collections = $this->_add_items_to_collections($all_items, $all_collections);
        return $collections;
    }

    /**
     * @param  Collection<int, Item>  $all_items
     * @param  Collection<int, Item>  $all_collections
     * 
     * @return array<int, array<mixed>>
     */ 
    private function _add_items_to_collections(Collection $all_items, Collection $all_collections)
    {
        $collections = [];

        foreach($all_collections as $r) {

            $collections[$r->id] = ['id'=>$r->id, 'name'=>$r->name, 'slug'=>$r->slug, 'content'=>$r->content, 'status_id'=>$r->status_id, 'group_id'=>$r->group_id, 'item_properties'=>$r->item_properties??null, 'collection_items'=>[]];

            // Select the items belonging to the collections
            $items = DB::table('item_collection')
            ->select('item_id')
            ->where('collection_item_id', '=', $r->id)
            ->get();

            foreach($items as $i) {
                // find the item in $all_items, and add it
                $item_with_properties = $all_items->first(function($item) use ($i) {
                    return $item->id == $i->item_id;
                });
                
                $collections[$r->id]['collection_items'][] = $item_with_properties;
            }
        }

        return $collections;
    }

    /**
     * 
     * @return array<int, array<mixed>>
     */
    public function all(): array
    {
        // A Collection is an Item of type_id = 30
        $getItem = new GetItem( ['item_type_id' => 30, 'language' => $this->data['language']] );
        $all_collections = $getItem->all();

        if(count($all_collections)==0) return [];        

        // Get all items with flattened item_properties
        $getItem = new GetItem( ['language' => $this->data['language']] );
        $all_items = $getItem->all();

        // Add items to the collection
        $collections = $this->_add_items_to_collections($all_items, $all_collections);
        return $collections;
        
    }

    /**
     * Select all published collections on the map
     * 
     * @return array<int, array<mixed>>
     */
    public function all_collections(): array
    {
        // only published items of item_type 30 are collections on the map
        $this->data = array_merge($this->data, ['items.item_type_id'=>30, 'items.status_id'=>20]);
        return $this->all_with_default_nl();
    } 

    /**
     * @return array<int, array<mixed>>
     */
    public function all_with_default_nl(): array
    {
        // look for items in the requested language, and use 'nl' records as fallback
        $preferred_language = $this->data['language'];

        $query = DB::table('items')
                    ->select('items.*', 'users.group_id')
                    ->join('users', 'items.user_id', '=', 'users.id')
                    ->leftJoin('items as i2', function($join) use ($preferred_language) {
                        $join->on('i2.id', '=', 'items.id')
                                ->where('i2.language', '=', $preferred_language)
                                ->where('items.language', '<>', $preferred_language);
                    })
                    ->whereIn('items.language', [$preferred_language, 'nl'])
                    ->whereNull('i2.id');
                   
        // any parameters to add to the query?        
        foreach($this->data as $k => $v) {
            
            if($k==='language') continue;
                        
            if(strpos($v, ',')!==false) {
                $array = explode(',', $v);
                $query->whereIn($k, $array);
            } else {
                $query->where($k, $v);
            }
        }               
        $all_collections = $query->get();

        // look for item_properties in the requested language, and use 'nl' records as fallback
        $query = DB::table('item_properties')
                    ->select('item_properties.*')
                    ->join('items', 'item_properties.item_id', '=', 'items.id')
                    ->leftJoin('item_properties as p2', function($join) use ($preferred_language) {
                        $join->on('p2.id', '=', 'item_properties.id')
                                ->where('p2.language', '=', $preferred_language)
                                ->where('item_properties.language', '<>', $preferred_language);
                    
                    })
                    ->whereIn('item_properties.language', [$preferred_language, 'nl'])                        
                    ->whereNull('p2.id')
                    ->where('items.item_type_id', '=', 30);
                    
        $item_properties = $query->get();

        // add flattened properties
        $all_collections->transform( function ($item) use ($item_properties) {
            $item->item_properties = (object)[];
            
            foreach($item_properties as $r) {
                if($r->item_id===$item->id) {
                    $item->item_properties->{$r->key} = $r->value;
                }
            }
            
            return $item;
        });


        // Get items in the requested language, and use 'nl' records as fallback
        $getItem = new GetItem( ['language' => $this->data['language']] );
        $all_items = $getItem->all_with_default_nl();

        $collections = $this->_add_items_to_collections($all_items, $all_collections);

        return $collections;
        
    }

}

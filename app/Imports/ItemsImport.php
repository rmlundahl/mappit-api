<?php

namespace App\Imports;

use App\Models\Item;
use App\Services\Item\CreateItem;
use App\Services\Item\UpdateItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ItemsImport implements ToCollection, WithHeadingRow
{
    private const ITEM_COLUMNS = ['id','language','item_type_id','parent_id','external_id','name','slug','content','user_id','status_id'];

    /**
     * @param  Collection<(int|string), mixed>  $rows
     * 
     * @return void
     */

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) 
        {
            $rowdata = [];
            $rowdata['item_properties'] = [];
            
            foreach ($row as $key => $value) {

                // we are only interested in the named keys of each row
                if(gettype($key) != 'string') continue;
                
                // where to store this data, in table 'items' or 'item_properties'?
                if(in_array($key, self::ITEM_COLUMNS)) {
                    // item data
                    $rowdata[$key] = $value;
                } else {
                    // item_property data
                    $rowdata['item_properties'][$key] = $value;
                }
            }

            // a row should have the 'external_id' and 'name'
            if(!isset($rowdata['external_id']) || !isset($rowdata['name'])) continue;

            // the 'external_id' should not be empty
            if(empty($rowdata['external_id'])) continue;
            
            // set defaults for missing values
            if(!isset($rowdata['language'])) $rowdata['language'] = 'nl';
            if(!isset($rowdata['item_type_id'])) $rowdata['item_type_id'] = 10;  
            if(!isset($rowdata['user_id'])) $rowdata['user_id'] = 1;
            if(!isset($rowdata['status_id'])) $rowdata['status_id'] = 20;

            // Try to find existing item
            $item = Item::where(['external_id' => $rowdata['external_id'], 'language' => $rowdata['language']])->first();
            if($item) {
                // update
                $updateItem = new UpdateItem( $rowdata, $item );
                $item = $updateItem->update();
            } else {
                // insert
                $createItem = new CreateItem( $rowdata );
                $newItem = $createItem->save();
            }
           
/*
            // Find the item by external_id or create a new instance
            $item = Item::firstOrNew(['external_id' => $rowdata['external_id'], 'language' => $rowdata['language']]);

            $item->fill($rowdata);

            // Save the item (either updates or inserts)
            $item->save();
*/
        }
    }
}
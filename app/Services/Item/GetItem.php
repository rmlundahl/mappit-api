<?php

namespace App\Services\Item;

use App\Models\Item;

use App;

class GetItem {
    
    private $data;

    public function __construct(array $parameterData)
    {
        $this->data = $parameterData;
    }


    public function all()
    {
        // leave out deleted items
        $query = Item::where('status_id', '<>', 99);

        // language available in request?
        if( !empty($this->data['language']) ) {
            $query->where('language', $this->data['language']);
        } else {
            $query->where('language', App::getLocale());
        }
        
        // any other parameters to add to the query?
        if( !empty($this->data) ) {
            foreach($this->data as $k => $v) {
                if($k!=='language') $query->where($k, $v);
            }
        }
        
        return $query->get();

    }
}

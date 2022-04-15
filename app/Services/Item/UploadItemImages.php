<?php

namespace App\Services\Item;

use App\Models\Item;
use Storage;

class UploadItemImages{
    
    private $item_property;
    
    public function __construct( $item_property )
    {
        $this->item_property = $item_property;
    }

    public function saveImage ($image)
    {
        $url = $this->_saveFile( $image );
        $this->item_property->key = 'uitgelichte_afbeelding';
        $this->item_property->value = $url;
        $this->item_property->save();
    }

    private function _saveFile ($file)
    {
        $path = Storage::put( '/public/items/'.$this->item_property->item_id, $file );
        $fileURL = env('APP_URL').'/storage/'.str_replace( 'public/', '', $path );
        return $fileURL;
    }
}

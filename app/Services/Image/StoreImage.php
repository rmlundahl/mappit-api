<?php

namespace App\Services\Image;

use Log, Storage;

class StoreImage {
    
    private $image;
    
    public function __construct( $image, $sub_directory='', $clear_sub_directory=false )
    {
        $this->image = $image;
        $this->sub_directory = $sub_directory;
        $this->clear_sub_directory = $clear_sub_directory;
    }

    public function store ()
    {
        if( !empty($this->image['item_id']) && $this->clear_sub_directory) {
            
            // Get all files in a directory
            $files =   Storage::allFiles('/items/'.$this->image['item_id'].$this->sub_directory);

            // Delete Files
            Storage::delete($files);
        }
        
        $file = $this->image['file'];
        $path = $file->storeAs('/items/'.$this->image['item_id'].$this->sub_directory, $file->getClientOriginalName());
        $fileURL = env('APP_URL').'/storage/'.str_replace( 'public/', '', $path );
        return $fileURL;        
    }
}

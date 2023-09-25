<?php

namespace App\Services\Image;

use Log, File;

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
        if( !empty($this->image['item_id']) && !empty($this->sub_directory) && $this->clear_sub_directory) {
            
            // Get all files in a directory
            $dir = storage_path('app/public').'/items/'.$this->image['item_id'].$this->sub_directory.'/';
            $files = File::allFiles($dir);
            
            // Delete Files
            File::delete($files);
        }

        $file = $this->image['file'];
        $filename = \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        $filename =  $filename.'.'.$extension;

        $path = $file->storeAs('items/'.$this->image['item_id'].$this->sub_directory, $filename, 'public');
        
        return $filename;        
    }
}

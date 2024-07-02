<?php

namespace App\Services\Image;

use File;

class StoreImage {
    
    /**
     * @var array<string, mixed>
     */
    private array $image;
    
    private string $sub_directory;
    private bool $clear_sub_directory;

    /**
     * @param  array<string, mixed>  $image
     */
    public function __construct( array $image, string $sub_directory='', bool $clear_sub_directory=false )
    {
        $this->image = $image;
        $this->sub_directory = $sub_directory;
        $this->clear_sub_directory = $clear_sub_directory;
    }

    public function store(): string
    {
        $dir = storage_path('app/public').'/items/'.$this->image['item_id'].$this->sub_directory.'/';
        
        // create directory if necessary
        File::ensureDirectoryExists($dir);

        if( !empty($this->image['item_id']) && !empty($this->sub_directory) && $this->clear_sub_directory) {
            
            // Get all files in a directory
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

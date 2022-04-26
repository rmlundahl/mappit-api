<?php

namespace App\Services\Image\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Medium implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $sizes = explode('x', config('imagecache.dimensions.medium'));
        return $image->fit( $sizes[0],$sizes[1] )->encode('jpg', 100);
    }
}
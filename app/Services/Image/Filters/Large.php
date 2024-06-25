<?php

namespace App\Services\Image\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Large implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        $sizes = explode('x', config('imagecache.dimensions.large'));
        return $image->fit( (int) $sizes[0], (int) $sizes[1] )->encode('jpg', 100);
    }
}
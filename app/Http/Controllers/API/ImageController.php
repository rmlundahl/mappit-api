<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Requests\API\Image\StoreImageRequest;
use App\Services\Image\StoreImage;

use App, Log, Storage;

class ImageController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'delete']);
    }

    /**
     * Get a listing of files in the direcatory
     * @param  string  $path
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($path)
    {
        $files = Storage::disk('public')->allFiles($path);
        return response()->json( $files, 200 );
    }

    /**
     * Store an image in storage.
     *
     * @param  App\Http\Requests\API\Image\StoreImageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreImageRequest $request)
    {
        $storeImage = new StoreImage( $request->all() );
        $storeImage = $storeImage->store();
        return response()->json( $storeImage, 201 );
    }

}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index($path)
    {
        $files = Storage::allFiles($path);
        return response()->json( $files, 200 );
    }

    /**
     * Store an image in storage.
     *
     * @param  App\Http\Requests\API\Image\StoreImageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreImageRequest $request)
    {
        $storeImage = new StoreImage( $request->all() );
        $storeImage = $storeImage->store();
        return response()->json( $storeImage, 201 );
    }

    
    /**
     * Soft remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteItemRequest $request)
    {
        $item =Item::find(['id'=>$request->id, 'language'=>$request->language]);
        
        if (empty($item)) {
            return response()->json( [], 404 ); 
        }

        $deleteItem = new DeleteItem( $request->all(), $item );
        
        $item = $deleteItem->delete($deleteItem, $item);
        return response()->json( [], 204 );
    }
}

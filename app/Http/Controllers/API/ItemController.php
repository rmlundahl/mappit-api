<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

use App\Services\Item\GetItem;
use App\Services\Item\CreateItem;
use App\Services\Item\UpdateItem;
use App\Services\Item\DeleteItem;
use App\Http\Requests\API\Item\CreateItemRequest;
use App\Http\Requests\API\Item\UpdateItemRequest;
use App\Http\Requests\API\Item\DeleteItemRequest;

use App, Log, Validator;

class ItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['all_from_user', 'store', 'update', 'delete']);
    }

    /**
     * Get all published items with item_properties for use as marker on the Google Map
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all_markers(Request $request)
    {
        $getItem = new GetItem( $request->all() );
        $items = $getItem->all_markers();
        
        if (empty($items)) {
            return response()->json( [], 404 ); 
        }

        return response()->json( $items, 200 );
    }

    /**
     * Display a listing of the resource.
     *
     * * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $getItem = new GetItem( $request->all() );
        $items = $getItem->all();
        return response()->json( $items, 200 );
    }
    
    /**
     * Get a listing of all items a user can see, based on the user's role
     *
     * * @return \Illuminate\Http\JsonResponse
     */
    public function all_from_user(Request $request)
    {
        $getItem = new GetItem( $request->all() );
        $items = $getItem->all_from_user();
        
        if (empty($items)) {
            return response()->json( [], 404 ); 
        }
        return response()->json( $items, 200 );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\API\Item\CreateItemRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateItemRequest $request)
    {
        $createItem = new CreateItem( $request->all() );
        $newItem = $createItem->save();
        return response()->json( $newItem, 201 );
    }

    /**
     * Find the specified resource by primary key.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function find($id, Request $request)
    {
        if ( !is_numeric($id) ) {
            return response()->json( [], 404 );
        }

        $item_type_id = $request->get('item_type_id') ?? 10;
        if ( !is_numeric($item_type_id) ) {
            return response()->json( [], 404 );
        }

        $item = Item::where('id', $id)->where('item_type_id', $item_type_id)->where('language', App::getLocale())->with('user','item_properties')->first();
       
        if (empty($item)) {
            return response()->json( [], 404 ); 
        }
        return response()->json( $item, 200 );
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\API\Item\UpdateItemRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateItemRequest $request)
    {
        $item = Item::find(['id'=>$request->id, 'language'=>$request->language]);
        
        $updateItem = new UpdateItem( $request->all(), $item );
        $item = $updateItem->update();

        return response()->json( $item, 200 );
    }

    /**
     * Soft remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteItemRequest $request)
    {
        $item = Item::find(['id'=>$request->id, 'language'=>$request->language]);
        
        if (empty($item)) {
            return response()->json( [], 404 ); 
        }

        $deleteItem = new DeleteItem($item);
        
        $item = $deleteItem->delete();
        return response()->json( [], 204 );
    }
}

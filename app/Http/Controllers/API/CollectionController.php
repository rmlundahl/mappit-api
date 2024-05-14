<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

use App\Services\Collection\GetCollection;

use App\Services\Item\CreateItem;
use App\Services\Item\UpdateItem;
use App\Services\Item\DeleteItem;
use App\Http\Requests\API\Item\CreateItemRequest;
use App\Http\Requests\API\Item\UpdateItemRequest;
use App\Http\Requests\API\Item\DeleteItemRequest;

use App;

class CollectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'delete']);
    }
    
    /**
     * Get all published collections with items with item_properties for use on the Google Map
     *
     * @return \Illuminate\Http\Response
     */
    public function all_collections(Request $request)
    {
        $getCollection = new GetCollection( $request->all() );
        $collections = $getCollection->all_collections();
        
        if (empty($collections)) {
            return response()->json( [], 404 ); 
        }

        return response()->json( $collections, 200 );
    }

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $getCollection = new GetCollection( $request->all() );
        $collections = $getCollection->all();
        return response()->json( (array) $collections, 200 );
    }

    /**
     * Get a listing of all collections a user can see, based on the user's role
     *
     * * @return \Illuminate\Http\Response
     */
    public function all_from_user(Request $request)
    {
        $getCollection = new GetCollection( $request->all() );
        $collections = $getCollection->all_from_user();
        
        if (empty($collections)) {
            return response()->json( [], 404 ); 
        }
        return response()->json( $collections, 200 );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\API\Item\CreateItemRequest  $request
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function find($id, Request $request)
    {
        if ( !is_numeric($id) ) {
            return response()->json( [], 404 );
        }

        $item_type_id = $request->get('item_type_id') ?? 30;
        if ( !is_numeric($item_type_id) ) {
            return response()->json( [], 404 );
        }

        $item = Item::where('id', $id)->where('item_type_id', $item_type_id)->where('language', App::getLocale())->with('item_properties')->with('collection_items')->first();
       
        if (empty($item)) {
            return response()->json( [], 404 ); 
        }
        return response()->json( $item, 200 );
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\API\Item\UpdateItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemRequest $request)
    {
        $item = Item::find(['id'=>$request->id, 'language'=>$request->language]);
        
        $updateItem = new UpdateItem( $request->all(), $item );
        $item = $updateItem->update($updateItem, $item);

        return response()->json( $item, 200 );
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
        
        $item = $deleteItem->delete();
        return response()->json( [], 204 );
    }
}

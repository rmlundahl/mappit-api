<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

use App\Services\Item\CreateItem;
use App\Services\Item\UpdateItem;
use App\Services\Item\DeleteItem;
use App\Http\Requests\API\Item\CreateItemRequest;
use App\Http\Requests\API\Item\UpdateItemRequest;
use App\Http\Requests\API\Item\DeleteItemRequest;

use App, Log;

class ItemController extends Controller
{
    private $item;

    public function __construct(Item $item)
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'delete']);
        $this->item = $item;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $items = $this->item->all()->where('language', App::getLocale());
       return response()->json( $items, 200 );
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
    public function find($id)
    {
        $item = Item::find(['id'=>$id, 'language'=>App::getLocale()]);
       
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
        
        $item = $deleteItem->delete($deleteItem, $item);
        return response()->json( [], 204 );
    }
}

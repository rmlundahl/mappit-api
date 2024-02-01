<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

use App\Services\Collection\GetCollection;

use App;

class CollectionController extends Controller
{
    private $filter;

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'delete']);
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

}

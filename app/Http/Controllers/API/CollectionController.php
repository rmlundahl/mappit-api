<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Collection\GetCollection;

use App, Log;

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

}

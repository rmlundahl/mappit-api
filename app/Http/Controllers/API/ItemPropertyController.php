<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ItemProperty;
use Illuminate\Http\Request;

use App\Http\Requests\API\ItemProperty\BulkUpdateItemPropertiesRequest;
use App\Http\Requests\API\ItemProperty\CreateItemPropertyRequest;
use App\Services\ItemProperty\GetItemProperty;
use App\Services\ItemProperty\BulkUpdateItemProperty;
use App\Services\ItemProperty\CreateItemProperty;

use App;

class ItemPropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $getItemProperty = new GetItemProperty( $request->all() );
        $items = $getItemProperty->all();
        return response()->json( $items, 200 );
    }

    /**
     * Store a new item property.
     *
     * @param  \App\Http\Requests\API\ItemProperty\CreateItemPropertyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateItemPropertyRequest $request)
    {
        $createItemPropertyService = new CreateItemProperty($request->validated());
        $result = $createItemPropertyService->save();
        
        return response()->json($result, 201);
    }

    /**
     * Find the specified resource by primary key.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function find($id)
    {
        if ( !is_numeric($id) ) {
            return response()->json( [], 404 );
        }

        $itemProperty = ItemProperty::where('id', $id)->where('language', App::getLocale())->first();
       
        if (empty($itemProperty)) {
            return response()->json( [], 404 ); 
        }
        return response()->json( $itemProperty, 200 );
        
    }

    /**
     * Bulk update item properties matching the given key and value.
     *
     * @param  \App\Http\Requests\API\ItemProperty\BulkUpdateItemPropertiesRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpdate(BulkUpdateItemPropertiesRequest $request)
    {
        $bulkUpdateService = new BulkUpdateItemProperty($request->all());
        $result = $bulkUpdateService->update();
        
        return response()->json($result, 200);
    }
}
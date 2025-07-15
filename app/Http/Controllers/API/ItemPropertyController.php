<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ItemProperty\BulkUpdateItemPropertiesRequest;
use App\Services\ItemProperty\BulkUpdateItemProperty;

class ItemPropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Bulk update item properties matching the given key and value.
     *
     * @param  \App\Http\Requests\API\ItemProperty\BulkUpdateItemPropertiesRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkUpdate(BulkUpdateItemPropertiesRequest $request)
    {
        $bulkUpdateService = new BulkUpdateItemProperty($request->validated());
        $result = $bulkUpdateService->update();
        
        return response()->json($result, 200);
    }
}
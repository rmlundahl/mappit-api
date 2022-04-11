<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Filter;
use Illuminate\Http\Request;

// use App\Services\Filter\CreateFilter;
// use App\Services\Filter\UpdateFilter;
// use App\Services\Filter\DeleteFilter;
// use App\Http\Requests\API\Filter\CreateFilterRequest;
// use App\Http\Requests\API\Filter\UpdateFilterRequest;
// use App\Http\Requests\API\Filter\DeleteFilterRequest;

use App, Log;

class FilterController extends Controller
{
    private $filter;

    public function __construct(Filter $filter)
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'delete']);
        $this->filter = $filter;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_filters = $this->filter->all()->where('language', App::getLocale());
        $filters = [];
        $current_parent_id = 1000;

        foreach($all_filters as $r) {
            
            if($r['parent_id'] == null) {
                $filters[$r['id']] = ['slug'=> $r['slug'], 'elements'=>[]];
            }

            $current_parent_id = $r['parent_id'];
            
            if($r['parent_id']==$current_parent_id && $current_parent_id != null) {
                $filters[$current_parent_id]['elements'][] = ['value'=>$r['slug'], 'text'=> $r['name']];
            }
        }
        
        return response()->json( $filters, 200 );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\API\Filter\CreateFilterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFilterRequest $request)
    {
        $createFilter = new CreateFilter( $request->all() );
        $newFilter = $createFilter->save();
        return response()->json( $newFilter, 201 );
    }

    /**
     * Find the specified resource by primary key.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        $filter = Filter::find(['id'=>$id, 'language'=>App::getLocale()]);
       
        if (empty($filter)) {
            return response()->json( [], 404 ); 
        }
        return response()->json( $filter, 200 );
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\API\Filter\UpdateFilterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFilterRequest $request)
    {
        $filter = Filter::find(['id'=>$request->id, 'language'=>$request->language]);
        
        $updateFilter = new UpdateFilter( $request->all(), $filter );
        
        $filter = $updateFilter->update($updateFilter, $filter);
        return response()->json( $filter, 200 );
    }

    /**
     * Soft remove the specified resource from storage.
     *
     * @param  \App\Models\Filter  $filter
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteFilterRequest $request)
    {
        $filter =Filter::find(['id'=>$request->id, 'language'=>$request->language]);
        
        if (empty($filter)) {
            return response()->json( [], 404 ); 
        }

        $deleteFilter = new DeleteFilter( $request->all(), $filter );
        
        $filter = $deleteFilter->delete($deleteFilter, $filter);
        return response()->json( [], 204 );
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Filter;

use App;

class FilterController extends Controller
{
    private Filter $filter;

    public function __construct(Filter $filter)
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'delete']);
        $this->filter = $filter;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $all_filters = $this->filter->whereIn('language', ['nl', App::getLocale()])->get();
        $filters = [];
        $current_parent_id = 1000;

        foreach($all_filters as $r) {
            
            if($r['parent_id'] == null) {
                $filters[$r['id']] = ['slug'=> $r['slug'], 'elements'=>[]];
            }

            $current_parent_id = $r['parent_id'];
            
            if($r['parent_id']==$current_parent_id && $current_parent_id != null) {
                
                // only add 'nl' if not set yet
                if($r['language']=='nl' && !isset($filters[$current_parent_id]['elements'][$r['id']])) {
                    $filters[$current_parent_id]['elements'][$r['id']] = ['value'=>$r['slug'], 'text'=> $r['name']];
                }
                // always use transalation
                if($r['language']==App::getLocale()) {
                    $filters[$current_parent_id]['elements'][$r['id']] = ['value'=>$r['slug'], 'text'=> $r['name']];
                }
                
            }
        }

        // re-index the 'element' arrays: this is for javascript
        foreach($filters as $k => $v) {
            foreach($v as $_k => $_v) {
                if(is_array($_v)) {
                    // p(array_values($_v));
                    $filters[$k][$_k] = array_values($_v);
                }
            }
        }

        return response()->json( $filters, 200 );
    }
   
}

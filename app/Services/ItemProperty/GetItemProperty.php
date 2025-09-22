<?php declare(strict_types = 1);

namespace App\Services\ItemProperty;

use App\Models\ItemProperty;

use \Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

class GetItemProperty {
    
    /**
     * @var array<string, int|string>
     */
    private $data;

    /**
     * @param  array<string, int|string>  $parameterData
     */
    public function __construct(array $parameterData)
    {
        $this->data = $parameterData;
        
        if( empty($this->data['language']) ) {
            $this->data['language'] = App::getLocale();
        }
    }

    /**
     *  @return \Illuminate\Support\Collection<int, \App\Models\ItemProperty>.
     */  
    public function all(): \Illuminate\Support\Collection
    {

        $query = ItemProperty::query();

        // any parameters to add to the query?
        if( !empty($this->data) ) {
            foreach($this->data as $k => $v) {
                $v = (string) $v;
                if(strpos($v, ',')!==false) {
                    $array = explode(',', $v);
                    $query->whereIn($k, $array);
                } else {
                    $query->where($k, $v);
                }
            }
        }

        return $query->get();
    }

}

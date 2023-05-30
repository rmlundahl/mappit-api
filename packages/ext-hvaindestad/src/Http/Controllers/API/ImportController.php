<?php

namespace Mappit\ExtHvaindestad\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Mappit\ExtHvaindestad\Services\Import\ImportJsonData;
use Mappit\ExtHvaindestad\Imports\TypeAndIndustryImport;
use Maatwebsite\Excel\Facades\Excel;

use Storage;

class ImportController extends Controller
{
    private $importJsonData;
    private $data_translation;

    public function __construct(ImportJsonData $importJsonData)
    {
        $this->importJsonData = $importJsonData;
    }

    public function helloWorld()
    {
        dd('helloWorld');
    }

    public function import_json_data()
    {
        return $this->importJsonData->import_json_data();
    }

    public function import_type_and_industry()
    {
        $array  = Excel::toArray(new TypeAndIndustryImport, 'Lijst TYPE_INDUSTRY (SECTOR).xlsx');

        // only use the first sheet
        $data = $array[0];

        $first=true;
        foreach($data as $r) {
            if($first) {
                $first=false; continue;
            }
            
            if(empty($r[0]) && empty($r[1]) && empty($r[2]) && empty($r[4]) && empty($r[5]) && empty($r[6])) {
                break;
            }
            // s($r);
            // Type
            if( !empty($r[0]) && !empty($r[1]) && !empty($r[2]) ) {
                $_row = [];
                $_row['type']=urlencode($r[0]);
                $_row['partner']=urlencode($r[1]);
                $_row['sector']=urlencode($r[2]);
                $this->data_translation[] = $_row;
            }

            // Industry
            if( !empty($r[4]) && !empty($r[5]) && !empty($r[6])) {
                $_row = [];
                $_row['industry']=urlencode($r[4]);
                $_row['partner']=urlencode($r[5]);
                $_row['sector']=urlencode($r[6]);
                $this->data_translation[] = $_row;
            }
        }
        s('Import ready.');
        Storage::put('type_and_industry.json', json_encode($this->data_translation));
        p('File written. All done.');
    }
}

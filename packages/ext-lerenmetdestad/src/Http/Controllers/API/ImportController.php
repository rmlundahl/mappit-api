<?php

namespace Mappit\ExtLerenMetDeStad\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Mappit\ExtLerenMetDeStad\Imports\DatabaseImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Item;
use App\Models\ItemProperty;

use Batch, DB, Str;

class ImportController extends Controller
{
    private $item_properties = ['semester','type','thema','opleiding','locatie','korte_intro','samenvatting','link_voor_kaart_of_database'];
    private $insert_item_property_values = [];

    public function __construct()
    {
        
    }

    public function helloWorld()
    {
        dd('helloWorld');
    }

    // https://lerenmetdestadleiden.local/api/v1/lerenmetdestad/import/excel
    public function import_excel()
    {
        $array  = Excel::toArray(new DatabaseImport, 'Overzicht_voor_database_en_digitale_kaart_v9.xlsx');
        
        if(empty($array)) p('Geen Excel data');

        // only use the first sheet
        $data = $array[0];

        $this->_init_arrays();

        // Clear the items table
        DB::table('items')->truncate();

        $first=true;
        foreach($data as $r) {
            if($first) {
                $first=false; continue;
            }
           
            if( empty($r['titel_digitale_kaart']) ) {
                continue;
            }
            // s($r);

            // insert
            $item = new Item;
            $item->language = 'nl';
            $item->item_type_id = $this->_get_item_type_id($r);
            $item->external_id = $r['id'];
            $item->name = $r['titel_digitale_kaart'];

            $item->slug = Str::slug($r['titel_digitale_kaart']);
            $item->user_id = 1;
            $item->status_id = 20;
            $item->save();

            // after inserts, there is no id 
            if( empty($item->id) ) $item->id = DB::getPdo()->lastInsertId();
                                   
            // save project properties as item_property
            foreach($this->item_properties as $p) {
                if(!empty($r[$p])) $this->_save_item_property($item->id, $p, $r[$p]);
            }
            
            // Ik zoek een
            $this->_save_item_property($item->id, 'ik_zoek_een', $this->item_type_id_translation[$item->item_type_id]);

            // Faculteit
            if( !empty($r['faculteit']) ) {
                $_import_array = explode(',', $r['faculteit']);

                foreach($_import_array as $_faculteit) {
                    $this->_save_item_property($item->id, 'faculteit', $_faculteit);
                }
            }

            // Partners
            if( !empty($r['partners']) ) {
                $_import_array = explode(',', $r['partners']);

                foreach($_import_array as $_partner) {
                    if( in_array( trim($_partner), ['Incluzio','BuZz','SOL','Gemeente Leiden'] )) {
                        $this->_save_item_property($item->id, 'vaste_partner', $_partner);
                    } else {
                        $this->_save_item_property($item->id, 'partner', $_partner);
                    }
                }
            }

            // Type partner
            if( !empty($r['type_partner']) ) {
                $_import_array = explode(',', $r['type_partner']);

                foreach($_import_array as $_type_partner) {
                    $this->_save_item_property($item->id, 'type_partner', $_type_partner);
                }
            }

            // Geolocatie
            if( !empty($r['geolocatie']) ) {
                $_loc = explode(',', $r['geolocatie']);
                $this->_save_item_property($item->id, 'latitude', trim($_loc[0]));
                $this->_save_item_property($item->id, 'longitude', trim($_loc[1]));
            }

        }

        // Clear the item_properties table
        DB::table('item_properties')->truncate();

        // Batch inserts for item_property
        if( !empty($this->insert_item_property_values) ) {
            $itemProperty = new ItemProperty;
            $insert_columns = ['language','item_id','key','value','status_id'];
            $result = Batch::insert($itemProperty, $insert_columns, $this->insert_item_property_values);
            foreach($result as $_k => $_v) {
                $this->report[] = $_k.': '.$_v;
            }
        }

        s('Import ready.');
        // Storage::put('type_and_industry.json', json_encode($this->data_translation));
        p('Data stored in database. All done.');
    }

    private function _init_arrays() {        
                
        $this->insert_item_property_values = [];
       
        // init array for 'gebied'
        $this->postcode_translation = [
            '1011,1018' => 'Centrum',
            '1019,1019' => 'Amsterdam-Oost', // Oostelijk Havengebied: 1019
            '1020,1039' => 'Amsterdam-Noord',
            '1040,1049' => 'Amsterdam Westpoort',
            '1050,1059' => 'Amsterdam-West',
            '1060,1069' => 'Amsterdam Nieuw-West', 
            '1070,1083' => 'Amsterdam-Zuid',
            '1086,1099' => 'Amsterdam-Oost',
            '1100,1108' => 'Amsterdam-Zuidoost',
            '1109,1379' => 'Buiten Amsterdam',
            '1380,1384' => 'Weesp',
            '1385,9999' => 'Buiten Amsterdam'
        ];

        // init array for readable item_type_id
        $this->item_type_id_translation = [
            '101' => 'Hogeschool Leiden',
            '102' => 'Universiteit Leiden',
            '103' => 'mboRijnland',
            '104' => 'Samenwerking',
        ];


    }

    private function _get_item_type_id($r) {
        
        $item_type_id = 1; // 'unknown'

        $_search = trim($r['onderwijsinstelling']);

        if($_search=='Hogeschool Leiden') {
            $item_type_id = 101;
        }
        else if($_search=='Universiteit Leiden') {
            $item_type_id = 102;
        }
        else if($_search=='mboRijnland') {
            $item_type_id = 103;
        }
        else if($_search=='Hogeschool Leiden / Universiteit Leiden') {
            $item_type_id = 104;
        }
        else if($_search=='Samenwerking') {
            $item_type_id = 104;
        }

        return $item_type_id;
    }

    private function _save_item_property($item_id, $key, $value) {
        $key = strtolower($key);
        $value = trim($value);

         // insert
         $this->insert_item_property_values[] = [
            'language' => 'nl',
            'item_id' => $item_id,
            'key' => $key,
            'value' => $value,
            'status_id' => 20
        ];
    }
}

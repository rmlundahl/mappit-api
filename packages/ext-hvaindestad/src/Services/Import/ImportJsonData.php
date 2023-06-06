<?php

namespace Mappit\ExtHvaindestad\Services\Import;

use Illuminate\Support\Facades\Http;

use App\Models\Item;
use App\Models\ItemProperty;

use App, DB, Batch, Exception, Log, Storage, Str;

class ImportJsonData {
    
    private $item;
    private $existing_items;
    private $existing_item_ids;
    private $keep_item_ids;
    private $data_translation; // from type and industry in json to partner and sector in filter
    private $ik_zoek_een_translation; // from item_type_id to readable name
    private $project_properties = ['Latitude','Longitude','Straat','Postcode','Plaats','Startdatum','Einddatum','Website','Webartikel samenvatting','Afbeelding url', 'Ik zoek een'];
    private $report = [];

    public function __construct(Item $item, ItemProperty $itemProperty)
    {
        $this->item = $item;
        $this->itemProperty = $itemProperty;
    }

    public function import_json_data()
    {
        $response = Http::timeout(10)->get( config('exthvaindestad.import_json_data.api_url') );
        
        // If the status code is not >= 200 and < 300...
        if( !$response->successful() ) {

            // Log an error and abort
            Log::error("import_json_data error: ". $response->status());
            return;
        }
        
        if(App::environment() != 'testing') {
            // the json data is in the third line of the response
            $arr = explode("\n",$response->body());
            $json = json_decode($arr[2]);
            // p($json);
        } else {
            $json = json_decode(file_get_contents('tests/Feature/API/Item/stubs/response_200.json'), true);
        }

        $this->_init_arrays();
        
        $i=0;
        $time_before=microtime(true);
        ini_set('max_execution_time', '180'); // 180 seconds = 3 minutes
        
        // Clear the items table
        DB::table('items')->truncate();
        
        foreach($json as $r) {
            
            if( !$this->_has_longitude_and_latitude($r) ) continue;
            
            if(isset($this->keep_item_ids[$r->Id])) continue;

            $item_type_id = $this->_get_item_type_id($r);
            
            // insert
            $item = new Item;
            $item->language = 'nl';
            $item->item_type_id = $item_type_id;
            
            if ($item->item_type_id==1) continue;
            if( !empty($r->Id) ) $item->external_id = $r->Id;
            $item->name = $this->_get_name($r);
            $item->slug = $r->Id;
            $item->user_id = 1;
            $item->status_id = 20;
            $item->save();
            // after inserts, there is no id 
            if( empty($item->id) ) $item->id = DB::getPdo()->lastInsertId();
                        
            // add this item to array of items to keep
            $this->keep_item_ids[$r->Id] = $item->id;
                       
            // save project properties as item_property
            foreach($this->project_properties as $p) {
                if(!empty($r->$p)) $this->_save_item_property($item->id, $p, $r->$p);
                
                // derive '1e of 2e semester'
                if( $p=='Startdatum' && (!empty($r->$p)) ) {
                    
                    $month = explode('-', $r->$p)[1];
                    if( in_array($month,[1,8,9,10,11,12]) ) {
                        $semester=1;
                    } else {
                        $semester=2;
                    }
                    $this->_save_item_property($item->id, 'semester', $semester);
                }

                // derive 'status': 'actueel' or 'afgerond'                
                if( $p=='Einddatum') {

                    $_status = 'actueel';                    
                    if ( !empty($r->$p) && date("Y-m-d H:i") > $r->$p ) {
                        $_status = 'afgerond';
                    }
                    $this->_save_item_property($item->id, 'status', $_status);
                }                

                // derive 'gebied'
                if($p=='Postcode' && !empty($r->$p) ) {
                    $postcode = substr($r->$p,0,4);
                    foreach($this->postcode_translation as $k=>$v) {
                        $k = explode(',', $k);
                        if($k[0] <= $postcode && $postcode <= $k[1]) {
                            $this->_save_item_property($item->id, 'gebied', $v);
                            continue;
                        }
                    }
                    
                }

                // Save readable format from item_type_id 
                if($p=='Ik zoek een') {
                    $this->_save_item_property($item->id, 'ik_zoek_een', $this->ik_zoek_een_translation[$item_type_id]);
                }
            }            

            // save related item_properties 
            if ($item_type_id != 1) $this->_save_related_item_properties($item->id, $r);

            // $i++;
            // if($i==10) break;
        }

        // Batch updates for item
        if( !empty($update_item_values) ) {
            $item = new Item;
            $result = Batch::update($item, $update_item_values, 'id');
            $this->report[] = 'Number of items updated: '.$result;
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

        // fix slugs
        $all_items = Item::all();
        foreach($all_items as $r) {
            $r->slug = Str::slug($r->name);
            $r->save();
        }

        $time_after=microtime(true);
        $this->report[] = "Time taken to parse JSON: " . number_format($time_after - $time_before, 4) . " seconds";
        $this->report[] = 'Done.';

        Log::info(implode("\r", $this->report));
        
        // s(implode("<br />", $this->report));

        return response()->json( ['import'=>'ready'], 200 );
    }
    
    private function _init_arrays() {        
                
        // init array to keep track of id's that should be kept because they are updated or new
        $this->keep_item_ids = [];

        $this->insert_item_property_values = [];
        
        // read array with translations from type and industry
        $contents = Storage::get('type_and_industry.json');
        if(empty($contents)) {
            throw new Exception("The file 'type_and_industry.json' is missing");
        }
        $this->data_translation = json_decode($contents, true);

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
        $this->ik_zoek_een_translation = [
            '101' => 'Studentenproject',
            '102' => 'Onderzoeksproject',
            '103' => 'HvA Lab',
            '104' => 'HvA Campus',
            '105' => 'Centre of Expertise',
        ];

        // init array for 'SDG'
        $this->sdg_translation = [
            'No+poverty' => '1. Geen armoede',
            'Zero+hunger' => '2. Geen honger',
            'Good+health+and+well-being+for+people' => '3. Gezondheid',
            'Quality+education' => '4. Onderwijs',
            'Gender+equality' => '5. Gendergelijkheid',
            'Clean+water+and+sanitation' => '6. Schoon water',
            'Affordable+and+clean+energy' => '7. Duurzame energie',
            'Decent+work+and+economic+growth' => '8. Economische groei',
            'Industry%2C+Innovation%2C+and+Infrastructure' => '9. Infrastructuur',
            'Reducing+inequalities' => '10. Minder ongelijkheid',
            'Sustainable+cities+and+communities' => '11. Duurzame steden',
            'Responsible+consumption+and+production' => '12. Verantwoorde productie',
            'Climate+action' => '13. Klimaatactie',
            'Life+below+water' => '14. Leven in het water',
            'Life+on+land' => '15. Leven op het land',
            'Peace%2C+justice+and+strong+institutions' => '16. Vrede',
            'Partnerships+for+the+goals' => '17. Partnerschappen',
        ];
    }
   
    private function _save_item_property($item_id, $key, $value) {
        $key = strtolower($key);
        $value = urldecode($value);

         // insert
         $this->insert_item_property_values[] = [
            'language' => 'nl',
            'item_id' => $item_id,
            'key' => $key,
            'value' => $value,
            'status_id' => 20
        ];
    }

    private function _has_longitude_and_latitude($r) {
        if( empty($r->Longitude) ) {
            return false;
        }
        else if( empty($r->Latitude) ) {
            return false;
        }        
        return true;
    }

    private function _get_item_type_id($r) {

        $item_type_id = 1; // 'unknown'

        if( empty($r->Objecttype) ) return $item_type_id;
              
        if($r->Objecttype=='Project') {
            if($r->Type=='Onderwijs') $item_type_id = 101;
            elseif($r->Type=='Onderzoek') $item_type_id = 102;
        }
        // else if($r->Objecttype=='Organisatie' && isset($r->Rol) && $r->Rol=='HvA+Locatie') {
        else if($r->Objecttype=='Organisatie' && isset($r->Industry)) {
            if($r->Industry=='Lab') $item_type_id = 103; // HvA Lab
            elseif($r->Industry=='Kennisinstelling') $item_type_id = 104; // HvA Campus
            elseif($r->Industry=='Onderzoek') $item_type_id = 105; // Center of Expertise
        } 
               
        if($item_type_id==1) {
            // Log::error("item_type_id type can not be determined");
            // Log::debug(__METHOD__ . print_r($r, true));
        }

        return $item_type_id;
    }

    private function _get_name($r) {
        $name=null;
        
        if($r->Objecttype=='Project') $name = $r->Naam;
        elseif($r->Objecttype=='Organisatie') $name = $r->Naam;
        elseif($r->Objecttype=='Persoon') $name = $r->Naam;
        elseif($r->Objecttype=='Locatie') $name = $r->{'Locatie naam'};
        
        if(is_null($name)) {
            throw new Exception("'Name' can not be null");            
        }

        return urldecode($name);
    }

    private function _save_related_item_properties($item_id, $r) {
        $related = $r->Related ?? null;
        if( empty($related) ) return;
        
        foreach($related as $_r) {
            
            // Contactpersoon
            if( isset($_r->Rol) && $_r->Rol==('Contactpersoon') ) {
                if( !empty($_r->Naam) && !empty($_r->Email) ) {
                    $this->_save_item_property($item_id, 'contactpersoon_naam', $_r->Naam);
                    $this->_save_item_property($item_id, 'contactpersoon_email', $_r->Email);
                }
            }
            // Opleiding of lectoraat
            if( isset($_r->{'HvA Organisatie'}) && $_r->{'HvA Organisatie'}=='true') {
                if( isset($_r->Type) ) {
                    // Opleiding of lectoraat?
                    if(in_array($_r->Type, ['Bachelor','Minor','Master','Lectoraat'])) {
                        $this->_save_item_property($item_id, 'opleiding_of_lectoraat', $_r->Type);
                        if( !empty($_r->Naam) ) {
                            $this->_save_item_property($item_id, 'opleiding_of_lectoraat_naam', $_r->Naam);
                        }
                    }
                }
            }
            // Type
            if( isset($_r->Type) ) {
                foreach($this->data_translation as $_t) {
                    if( empty($_t['type']) ) continue;
                    if (in_array($_r->Type, $_t)) {
                        $this->_save_item_property($item_id, 'partner', $_t['partner']);
                        $this->_save_item_property($item_id, 'partner_naam', $_r->Naam);
                        $this->_save_item_property($item_id, 'sector', $_t['sector']);
                        $this->_save_item_property($item_id, 'sector_naam', $_r->Naam);
                    }
                }
            }
            // Industry
            if( isset($_r->Industry) ) {
                foreach($this->data_translation as $_t) {
                    if( empty($_t['industry']) ) continue;
                    if (in_array($_r->Industry, $_t)) {
                        $this->_save_item_property($item_id, 'partner', $_t['partner']);
                        $this->_save_item_property($item_id, 'partner_naam', $_r->Naam);
                        $this->_save_item_property($item_id, 'sector', $_t['sector']);
                        $this->_save_item_property($item_id, 'sector_naam', $_r->Naam);
                    }
                }
            }
            // SDG
            if( isset($_r->SDG) ) {
                $_sdg_array = explode('%3B', $_r->SDG);
                foreach($_sdg_array as $_sdg) {
                    $this->_save_item_property($item_id, 'sdg', $this->sdg_translation[$_sdg]);
                }
            }
        }
    }

}

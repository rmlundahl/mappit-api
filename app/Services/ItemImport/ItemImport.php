<?php

namespace App\Services\ItemImport;

use App\Models\Item;
use App\Models\ItemProperty;
use App\Models\ItemCollection;
use App\Imports\ItemsImport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;

use function Laravel\Prompts\error;

class ItemImport {
    
    private string $file;
    private bool $clearTables;

    public function __construct(string $file, bool $clearTables=false)
    {
        $this->file = $file;
        $this->clearTables = $clearTables;
    }

    public function import(): bool
    {
        if( !$this->_headings_are_valid() ) {
            return false;
        }

        if( $this->clearTables ) {
            $this->_clear_tables();
        }

        $result = Excel::import(new ItemsImport, $this->file);

        return true;
    }   
    private function _headings_are_valid(): bool
    {
        $headings = (new HeadingRowImport)->toArray($this->file);
        
        // The headings array contains an array of headings per sheet.
        // first row of first sheet contains our array of headings: 
        // check if required fields are present

        foreach(['external_id', 'name'] as $heading) {
            if( !in_array($heading, $headings[0][0]) ) {
                error("Invalid headings: '$heading' not found");
                return false;
            }
        }

        return true;
    }

    private function _clear_tables(): void
    {
        // Item::truncate();
        // ItemProperty::truncate();
        // ItemCollection::truncate();
        dump('tables cleared');
    }
}

<?php declare(strict_types = 1);

namespace App\Services\ItemProperty;

use App\Models\ItemProperty;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateItemProperty {
    
    /**
     * @var array<string, int|string|null>
     */
    private array $data;

    /**
     * @param  array<string, int|string|null>  $itemPropertyData
     */
    public function __construct(array $itemPropertyData)
    {
        $this->data = $itemPropertyData;
    }

    /**
     * Create and save a new item property
     */
    public function save(): ItemProperty
    {
        $itemProperty = new ItemProperty;
        
        $itemProperty->language = (string) $this->data['language'];
        $itemProperty->key = (string) $this->data['key'];
        $itemProperty->value = (string) $this->data['value'];
        $itemProperty->status_id = (int) ($this->data['status_id'] ?? 1);
        
        // Set item_id if provided (nullable)
        if (!empty($this->data['item_id'])) {
            $itemProperty->item_id = (int) $this->data['item_id'];
        }
        
        // Set parent_id if provided (nullable)
        if (!empty($this->data['parent_id'])) {
            $itemProperty->parent_id = (int) $this->data['parent_id'];
        }
        
        if ( $itemProperty->save() ) {
            // Necessary because the save() method does not return the last created 'id' in the model
            $itemProperty = ItemProperty::orderBy('id','desc')->first();
        }

        return $itemProperty;
    }
}

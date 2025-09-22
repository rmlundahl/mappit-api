<?php

namespace App\Services\ItemProperty;

use App\Models\ItemProperty;
use DB, Exception, Log;

class BulkUpdateItemProperty
{
    /**
     * @var array<string, mixed>
     */
    private array $data;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Update all item properties matching the key and old value
     *
     * @return array<string, mixed> Result of the update operation
     */
    public function update(): array
    {
        $key = $this->data['key'];
        $oldValue = $this->data['old_value'];
        $newValue = $this->data['new_value'];
        
        try {
            // Build the query to match properties by key and old value
            $query = ItemProperty::where('key', $key)
                ->where('value', $oldValue);
            
            // Add language condition if provided
            if (isset($this->data['language'])) {
                $query->where('language', $this->data['language']);
            }
            
            // Count affected rows before update
            $affectedCount = $query->count();
            
            if ($affectedCount === 0) {
                return [
                    'success' => true,
                    'message' => "No properties found matching key '{$key}' and value '{$oldValue}'",
                    'affected_count' => 0,
                    'errors' => []
                ];
            }
            
            // Begin transaction for the update operation only
            DB::beginTransaction();
            
            $query->update([
                'value' => $newValue,
                'status_id' => (int) ($this->data['status_id'] ?? 20)
            ]);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => "{$affectedCount} properties updated successfully",
                'affected_count' => $affectedCount,
                'errors' => []
            ];
            
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('BulkUpdateItemProperty->update(): ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Failed to update properties',
                'affected_count' => 0,
                'errors' => [$e->getMessage()]
            ];
        }
    }
}

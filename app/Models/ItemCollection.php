<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCollection extends Model
{
    protected $table = 'item_collection';
    protected $primaryKey = ['item_id', 'item_collection_id'];
    
    protected $fillable = [
        'item_id',
        'item_collection_id'
    ];

}

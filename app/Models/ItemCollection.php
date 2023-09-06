<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class ItemCollection extends Model
{
    use Compoships;
    use \App\Models\Traits\HasCompositePrimaryKey;

    protected $table = 'item_collection';
    protected $primaryKey = ['item_id', 'item_collection_id'];
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'item_collection_id'
    ];

}

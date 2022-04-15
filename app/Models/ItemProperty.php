<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

class ItemProperty extends Model
{
    use HasFactory;
    use Compoships;
    use \App\Models\Traits\HasCompositePrimaryKey;

    protected $table = 'item_properties';
    protected $primaryKey = ['id', 'language'];
    // public $incrementing = false;
    
    protected $fillable = [
        'id',
        'language',
        'item_id',
        'key',
        'value',
        'user_id',
        'status_id',
    ];
}

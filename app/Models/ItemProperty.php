<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemProperty extends Model
{
    use HasFactory;
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    use \App\Models\Traits\HasCompositePrimaryKey;

    protected $table = 'collections';
    protected $primaryKey = ['id', 'language'];
    public $incrementing = false;
    
    protected $fillable = [
        'id',
        'language',
        'name',
        'slug',
        'status_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Filter extends Model
{
    use HasFactory;
    use \App\Models\Traits\HasCompositePrimaryKey;
    use HasRecursiveRelationships;
    
    protected $table = 'filters';
    protected $primaryKey = ['id', 'language'];

    protected $fillable = [
        'id',
        'language',
        'parent_id',
        'name',
        'slug',
        'status_id',
    ];
}

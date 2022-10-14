<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Group extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;
    
    protected $table = 'groups';

    protected $fillable = [
        'parent_id',
        'name',
        'path',
        'description',
        'status_id',
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;
    use \App\Models\Traits\HasCompositePrimaryKey;

    protected $table = 'items';
    protected $primaryKey = ['id', 'language'];
    // public $incrementing = false;
    
    protected $fillable = [
        'id',
        'language',
        'item_type_id',
        'external_id',
        'name',
        'slug',
        'content',
        'user_id',
        'status_id',
    ];

    public function item_properties()
    {
        // return $this->hasMany(ItemProperty::class);
        return $this->hasMany('App\Models\ItemProperty', ['item_id','language'], ['id','language']);
    }

    // Mutator for slug attribute
    public function setSlugAttribute($slug) 
    {

        if (static::whereSlug($slug)->whereLanguage($this->attributes['language'])->exists()) {
            $slug = $this->incrementSlug($slug);
        }
    
        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug($slug) 
    {
        
        $original = $slug;
        $count = 2;

        while (static::whereSlug($slug)->whereLanguage($this->attributes['language'])->exists()) {
            $slug = "{$original}-" . $count++;
        }
    
        return $slug;
    }
}

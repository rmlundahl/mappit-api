<?php

namespace App\Models;

use Awobaz\Compoships\Database\Eloquent\Relations\BelongsTo;
use Awobaz\Compoships\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int|null $group_id
 * @property object $item_properties
 */
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
        'parent_id',
        'external_id',
        'name',
        'slug',
        'content',
        'user_id',
        'status_id',
    ];

    /**
     * @return HasMany<\App\Models\ItemProperty>
     */
    public function item_properties(): HasMany
    {
        return $this->hasMany('App\Models\ItemProperty', ['item_id','language'], ['id','language']);
    }

    /**
     * @return HasMany<\App\Models\ItemCollection>
     */
    public function collection_items(): HasMany
    {
        return $this->hasMany('App\Models\ItemCollection', ['collection_item_id'], ['id']);
    }

    /**
     * @return BelongsTo<\App\Models\User, \App\Models\Item>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    // Mutator for slug attribute
    public function setSlugAttribute(string $slug): void
    {
        if (static::whereSlug($slug)->whereLanguage($this->attributes['language'])->where('id', '<>', $this->id)->exists()) {
            $slug = $this->incrementSlug($slug);
        }
    
        $this->attributes['slug'] = $slug;
    }

    public function incrementSlug(string $slug): string
    {
        $original = $slug;
        $count = 2;

        while (static::whereSlug($slug)->whereLanguage($this->attributes['language'])->exists()) {
            $slug = "{$original}-" . $count++;
        }
    
        return $slug;
    }
}

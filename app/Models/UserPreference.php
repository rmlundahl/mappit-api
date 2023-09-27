<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $table = 'user_preferences';
    
    protected $fillable = [
        'user_id',
        'key',
        'val',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

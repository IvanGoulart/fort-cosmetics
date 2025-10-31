<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cosmetic extends Model
{
    protected $fillable = [
        'api_id',
        'name',
        'type',
        'rarity',
        'image',
        'price',
        'is_new',
        'is_shop',
        'release_date',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_cosmetics')
                    ->withTimestamps()
                    ->withPivot('returned');
    }
}

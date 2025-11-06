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
        'bundle_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_cosmetics')
                    ->withTimestamps()
                    ->withPivot('returned');
    }

    public function items()
    {
        return $this->hasMany(Cosmetic::class, 'bundle_id'); // supondo que os itens tenham um bundle_id
    }

    public function bundle()
    {
        // O bundle “pai” deste cosmético (se ele for um item dentro de um bundle)
        return $this->belongsTo(Cosmetic::class, 'bundle_id');
    }
}

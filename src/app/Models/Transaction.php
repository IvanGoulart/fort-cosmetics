<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'cosmetic_id',
        'type',
        'amount',
        'executed_at',
    ];

    public $timestamps = false; // opcional, já usamos executed_at

    public function cosmetic()
    {
        return $this->belongsTo(Cosmetic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
    ];

    public function thing(): HasOne
    {
        return $this->hasOne(Thing::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Thing extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'photo',
        'longitude',
        'latitude',
        'shop_id',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function order(): belongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

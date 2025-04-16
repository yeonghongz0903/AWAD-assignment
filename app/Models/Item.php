<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    protected $fillable = ['name', 'price'];

    /**
     * The shopping carts that include this item.
     */
    public function shoppingCarts(): BelongsToMany
    {
        return $this->belongsToMany(ShoppingCart::class, 'cart_item')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}

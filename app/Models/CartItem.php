<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_item';

    protected $fillable = [
        'shopping_cart_id',
        'item_id',
        'quantity',
    ];

    public $timestamps = false;
}

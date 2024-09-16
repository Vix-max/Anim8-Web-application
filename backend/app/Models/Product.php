<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_category',
        'quantity',
        'buy_price',
        'sell_price',
        'product_description',
        'product_image',
    ];
}

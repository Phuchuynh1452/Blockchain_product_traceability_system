<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'price',
        'total_price',
        'product_id',
        'list_saleroom',
        'shelf_life',
        'thumb'
    ];
}

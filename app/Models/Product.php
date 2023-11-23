<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'detail',
        'price',
        'quantity',
        'thumb',
        'menu_id',
        'supplier_id',
        'farmer_id',
        'block',
        'block_number'
    ];


    public function menu(){
        return $this->hasOne(Menu::class,'id','menu_id')
            ->withDefault('');
    }
}

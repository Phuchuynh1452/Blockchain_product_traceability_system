<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class market extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'businesscode',
        'thumb',
        'boss_name'
    ];
}

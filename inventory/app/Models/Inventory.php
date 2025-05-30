<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    protected $fillable = [
        'item_name',
        'brand_name',
        'category',
        'quantity',
        'max_quantity',
        'price',
        'status',
        'is_archived',
    ];
}

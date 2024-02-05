<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'ean',
        'image',
        'order_id',
        'country',
        'num_of_sales',
        'weight', //in kg
        'purchase_place',
        'purchase_price',
        'width',
        'length',
        'height',
        'number_of_pieces',
        'note',
        'content',
        'bol_account_id',
    ];


}

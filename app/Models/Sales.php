<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'seller_id',
        'item_description',
        'client_cod',
        'payment_method',
        'value',
        'payment_installments',
    ];
}

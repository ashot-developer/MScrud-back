<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $fillable = ['sku', 'product_name', 'product_qty','input_price', 'sale_price', 'sale_date', 'created_at', 'updated_at'];

    public static function getSaledBySku($sku)
    {
        return Sale::where('sku', $sku)->first();
    }

     
}

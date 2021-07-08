<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = ['sku', 'product_name', 'input_price', 'sale_price', 'product_qty', 'created_at', 'updated_at'];

    public static function getProductId($sku) 
    {
       return Product::where('sku', $sku)->first();
    }

    public static function getProductBySku($sku)
    {
        return Product::where('sku', $sku)->first();
    }
    
    public static function getPriceBySku($sku){
        $saled = Product::where('sku', $sku)->first();
        return $saled->sale_price;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = DB::table('products')->orderBy('id', 'desc')
        ->select('id','sku', 'product_name', 'input_price', 'sale_price', 'product_qty')
        ->get();

        return $products->toArray();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $product = Product::create([
            'sku' => $request->input('sku'),
            'product_name' => $request->input('product_name'),
            'input_price' => $request->input('input_price'),
            'sale_price' => $request->input('sale_price'),
            'product_qty' => $request->input('product_qty'),
        ]);
    
        if($product->save()){
            return $product;
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->sku = $request->input('sku');
        $product->product_name = $request->input('product_name');
        $product->input_price = $request->input('input_price');
        $product->sale_price = $request->input('sale_price');
        $product->product_qty = $request->input('product_qty');

        if($product->save()){
            return $product;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }

    public function getSkus() {
        $productSkus = DB::table('products')->pluck('sku')->toArray();;

        return $productSkus;
    }
}

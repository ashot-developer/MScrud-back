<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use Illuminate\Support\Carbon;
use App\Models\Product;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $saledProducts = DB::table('sales')->orderBy('id', 'desc')
        ->select('id','sku', 'product_name', 'sale_date', 'product_qty')
        ->get();

        return $saledProducts->toArray();
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
        
        $product = Product::getProductBySku($request->input('sku'));

        $discount = $product->product_qty - $request->input('qty');
        $product->product_qty = $discount;
        $product->save();


        $saledProduct = Sale::create([
            'sku' => $request->input('sku'),
            'product_name' => $product->product_name,
            'sale_date' => Carbon::now()->format('d.m.Y H:i'),
            'input_price' => $product->input_price,
            'sale_price' => $product->sale_price,
            'product_qty' =>  $request->input('qty'),
        ]);
    
        if($saledProduct->save()){
            return $saledProduct;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $saledProduct = Sale::find($id);
        $product = Product::getProductBySku($saledProduct->sku);

        if($product){
            $count = $product->product_qty + $saledProduct->product_qty;
            $pr = Product::find($product->id);
            $pr->product_qty = $count;
    
            $pr->save();

            if(Sale::destroy($id)){
                return $pr;
            }
        }else{
            
            return Sale::destroy($id);
        }


        

    }
}

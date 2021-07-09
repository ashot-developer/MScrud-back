<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Carbon;

class IncomeController extends Controller
{
    public $incomByM = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    public $incomArr = [];

    public function getBlackIncome(){
        $saledProducts = Sale::all()->toArray();
        foreach($saledProducts as $saledProduct){


            $income = $saledProduct['sale_price'] * $saledProduct['product_qty'];
            $date = $saledProduct['sale_date'];
            $index = (int)Carbon::parse($saledProduct['sale_date'])->format('m');
            $oldPrice = $this->incomByM[$index - 1];
            $newPrice = $oldPrice + $income;
            $this->incomByM[$index - 1] = $newPrice;
                
            
        }

        return $this->incomByM;
    }

    public function getIncome(Request $request) {

        if($request->input('to')){
            $saledProducts = Sale::whereBetween('sale_date', [$request->input('from') . ' 00:00', $request->input('to') . ' 23:59'])->get();
        }else{
            $from = str_replace('_', '.', collect($request->all())->keys()[0]);
            $saledProducts = Sale::whereBetween('sale_date', [$from . ' 00:00', $from . ' 23:59'])->get();
        }
        
        $clearIncome = $blackIncome = 0;
        foreach($saledProducts as $saledProduct){

            $clearIncome += ($saledProduct['sale_price'] - $saledProduct['input_price']) * $saledProduct['product_qty'];
            $blackIncome += $saledProduct['sale_price'] * $saledProduct['product_qty'];
         
            
        }

        return [ 'clear'=> $clearIncome, 'black' => $blackIncome ];
    }

}

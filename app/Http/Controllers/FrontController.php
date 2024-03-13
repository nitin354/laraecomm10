<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(){
        $product=Product::where('is_featured','Yes')->where('status',1)->get();
        $data['product'] =$product;
        return view('front.home',$data);

    }
}

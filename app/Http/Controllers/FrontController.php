<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(){
        $featured_product=Product::where('is_featured','Yes')->where('status',1)->get();
        $data['featured_product'] =$featured_product;
        $latest_product=Product::orderBy('id','DESC')->where('status',1)->take(8)->get();
        $data['latest_product'] =$latest_product;
        return view('front.home',$data);

    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\admin\subCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductSubCategoryController extends Controller
{
    public function index(Request $request){
        if(!empty($request->category_id)){
            $subCategory = subCategory::where("category_id",$request->category_id)->orderBy('name','ASC')->get();
            return response()->json([
                'status'=>true,
                'subCategory'=> $subCategory
            ]);

        }else{
            return response()->json([
                'status'=>false,
                'subCategory'=> []
            ]);

        }
    }

   
}

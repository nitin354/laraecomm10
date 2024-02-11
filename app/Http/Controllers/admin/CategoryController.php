<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
   public function index(Request $request){

      $categories = Category::latest();
      if($request->get("search")){
         $categories = $categories->where("name","LIKE","%".$request->get("search")."%");
      }
    $categories = $categories->paginate(10);
    return view("admin.category.list",compact("categories"));

   }


   public function create(){
    //$categories = Category::all();
    return view("admin.category.create");
   }
   public function store(Request $request){

    $validator =Validator::make($request->all(),[
        "name"=> "required",
        "slug"=>["required","unique:categories"],
     ]);

     if($validator->passes()){


        $category = new Category();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->status = $request->status;

        $category->save();
        
        $request->session()->flash("success","category added successfully");
        return response()->json(["status"=>true,"message"=>"category added"]);

     }else{
        return response()->json(["status"=>false,"error"=>$validator->errors()]);
     }

   }

   public function show($id){
   }

   public function edit($id){
   }

   public function update(Request $request, $id){
   }

   public function destroy($id){

   }    


}

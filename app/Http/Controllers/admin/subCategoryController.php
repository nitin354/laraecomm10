<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\admin\subCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class subCategoryController extends Controller
{

    public function index(Request $request){
        $subcategories = subCategory::select('sub_category.*','categories.name as categoryName')->latest('id')->leftJoin('categories','categories.id','sub_category.category_id');
        if($request->get("search")){
           $subcategories = $subcategories->where("sub_category.name","LIKE","%".$request->get("search")."%");
           $subcategories = $subcategories->orWhere("categories.name","LIKE","%".$request->get("search")."%");
        }
      $subcategories = $subcategories->paginate(10);
      return view("admin.sub_category.list",compact("subcategories"));
      
    }
    public function create(){

        $category = Category::orderBy("name","ASC")->get();
        $data['category']= $category;

        return view("admin.sub_category.create", $data);

    }

    public function store(Request $request){

       $validate=Validator::make($request->all(),[
        "name"=> "required",
        "slug"=> "required|unique:sub_category",
        "category"=>"required",
        "status"=> "required"
       ]);


       if($validate->passes()){
        $subcategory = new subCategory();
        $subcategory->name = $request->name;
        $subcategory->slug = $request->slug;
        $subcategory->category_id = $request->category;
        $subcategory->status = $request->status;

        $subcategory->save();


        
        $request->session()->flash("success","Sub category added successfully");
        return response()->json(["status"=>true,"message"=>"Sub category added successfully"]);
      
       }else{
        return response()->json([
            "status"=> false,
            "error"=> $validate->errors()
        ]);
       }

    }

    public function edit($id,Request $request){
        $category = Category::orderBy('name','ASC')->get();
        $subCateg = SubCategory::find($id);

        $data["subCategory"]= $subCateg;
        $data["category"]= $category;


        return view("admin.sub_category.edit", $data);
    }

    public function update(Request $request, $id){

        $subcategory = subCategory::find($id);

        $validate=Validator::make($request->all(),[
            "name"=> "required",
            "slug"=> "required|unique:sub_category,slug,".$subcategory->id.",id",
            "category"=>"required",
            "status"=> "required"
           ]);
    
    
           if($validate->passes()){
            
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->category_id = $request->category;
            $subcategory->status = $request->status;
    
            $subcategory->save();
    
    
            
            $request->session()->flash("success","Sub category Updated successfully");
            return response()->json(["status"=>true,"message"=>"Sub category Updated successfully"]);
          
           }else{
            return response()->json([
                "status"=> false,
                "error"=> $validate->errors()
            ]);
           }
    }

    public function destroy($id,Request $request){
        
        subCategory::find($id)->delete();
        $request->session()->flash("success","Sub category Deleted successfully");
        return response()->json(["status"=>true,"message"=> "Sub category Deleted successfully"]);
    }
}

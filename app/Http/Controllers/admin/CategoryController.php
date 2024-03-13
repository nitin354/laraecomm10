<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
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

//save image

      if(!empty($request->image_id)){
         $tempImage =TempImage::find($request->image_id);
         $extArray = explode(".",$tempImage->name);
         $ext =last($extArray);

         $newImageName = $category->id."-".time().".".$ext;

         $sPath = public_path() ."/temp/". $tempImage->name;
         $dPath = public_path() ."/uploads/category/". $newImageName;
         
      
         File::copy($sPath,$dPath);
         $sPath1 = public_path() ."/temp/thumb/". $tempImage->name;
         $dPath1 = public_path() ."/uploads/category/thumb/". $newImageName;
         File::copy($sPath1,$dPath1);
         
         $category->image = $newImageName;
         $category->save();

         
       


      }

        
        $request->session()->flash("success","category added successfully");
        return response()->json(["status"=>true,"message"=>"category added"]);

     }else{
        return response()->json(["status"=>false,"error"=>$validator->errors()]);
     }

   }


   public function edit($id,Request $request){

      $category = Category::find($id);
      if(empty($category)){
         return redirect("category.index");

      }

      return view('admin.category.edit',compact('category'));


   }

   public function update(Request $request, $id){

      $category = Category::find($id);
      if(empty($category)){
         return response()->json(['status'=>false,'notFound'=>true,message=> 'Category Not Found']);

      }
      $validator =Validator::make($request->all(),[
         "name"=> "required",
         "slug"=>'required|unique:categories,slug,'.$category->id.',id',
      ]);
      
 
      if($validator->passes()){
        
         $category->name = $request->name;
         $category->slug = $request->slug;
         $category->status = $request->status;
 
         $category->save();
 
 //save image
       if(!empty($request->image_id)){
          $tempImage =TempImage::find($request->image_id);
          $extArray = explode(".",$tempImage->name);
          $ext =last($extArray);
 
          $newImageName = $category->id."-".time().".".$ext;
 
          $sPath = public_path() ."/temp/". $tempImage->name;
          $dPath = public_path() ."/uploads/category/". $newImageName;
 
          File::copy($sPath,$dPath);


          $sPath1 = public_path() ."/temp/thumb/". $tempImage->name;
          $dPath1 = public_path() ."/uploads/category/thumb/". $newImageName;
          File::copy($sPath1,$dPath1);
        

         //  $dPath = public_path() ."/uploads/category/thumb/". $newImageName;
          
         //  $image = ImageManager::imagick()->read($dPath);
         //    // resize to 300 x 200 pixel
         //  $image->resize(300, 200);
         //  $image->save($dPath);

          $category->image = $newImageName;
          $category->save();
 
       }

         $request->session()->flash("success","category Updated successfully");
         return response()->json(["status"=>true,"message"=>"category added"]);
 
      }else{
         return response()->json(["status"=>false,"error"=>$validator->errors()]);
      }
 
   }

   public function destroy($id,Request $request){
      $category = Category::find($id);
      if(empty($category)){
      return redirect("category.index");

      }
         
      $dPath = public_path() ."/uploads/category/". $category->image;

      File::delete($dPath);
      $category->delete();


      $request->session()->flash("success","category deleted successfully");
      return response()->json(["status"=>true,"message"=>"category deleted successfully"]);



   }    


}

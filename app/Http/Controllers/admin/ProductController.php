<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Image;
class ProductController extends Controller
{
    public function index(Request $request){

        $products = Product::latest();
        if($request->get("search")){
           $products = $products->where("name","LIKE","%".$request->get("search")."%");
        }
      $products = $products->paginate(10);
      return view("admin.products.list",compact("products"));
       
    }
    public function create(Request $request){
        $data=[];
        $category = Category::orderBy("name","Asc")->get();
        $brand= Brand::orderBy("name","Asc")->get();
        $data["category"]=$category;
        $data["brand"]=$brand;
        return view("admin.products.create", $data);
    }

    public function store(Request $request){
        $rules = [
            'title'=> 'required',
            'slug'=> 'required|unique:products',
            'price'=> 'required|numeric',
            'sku'=> 'required|unique:products',
            'track_qty'=> 'required|in:Yes,No',
            'category'=> 'required|numeric',
            'is_featured'=> 'required|in:Yes,No',
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';

        }
        $validate=Validator::make($request->all(), $rules);

        if($validate->fails()){
            return response()->json([
                'status'=>false,
                'errors'=> $validate->errors()
            ]);

        }else{

            $product = new Product;

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();


            //save gallery pics
            if(!empty($request->image_array)){
                foreach ($request->image_array as $temp_image_id) {
                    $tempimage = TempImage::find($temp_image_id);
                    $extarray = explode('.',$tempimage->name);
                    $ext = last($extarray);
                    $productimage = new ProductImages();
                    $productimage->product_id =$product->id;
                    $productimage->image = 'NULL';
                    $productimage->save();

                    $imgname=$product->id.'-'.$productimage->id.'-'.time().'.'.$ext;
                    $sPath = public_path() ."/temp/". $tempimage->name;
                    $dPath = public_path() ."/uploads/product/". $imgname;
                    
                    File::copy($sPath,$dPath);
                    $productimage->image =$imgname;
                    $productimage->save();

                }

            }

            $request->session()->flash('success','Product Added Successfully');

            return response()->json([
                'status'=>true,
                'message'=> 'Product Added Successfully'
            ]);
            


        }

    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;

use Intervention\Image\Drivers\Gd\Driver;
class ProductImageController extends Controller
{
    public function update(Request $request){

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        
        $sPath = $image->getPathName();


        $productimage = new ProductImages();
        $productimage->product_id =$request->product_id;
        $productimage->image = 'NULL';
        $productimage->save();

        $imgname=$request->product_id.'-'.$productimage->id.'-'.time().'.'.$ext;
        $productimage->image = $imgname;
        $productimage->save();


        
       
        $dPath = public_path() ."/uploads/product/large/". $imgname;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($sPath);
        $image->scaleDown(width:1400);
        $image->save($dPath);

        //small 
        
        $dPath = public_path() ."/uploads/product/small/". $imgname;
        $managers = new ImageManager(new Driver());
        $images = $managers->read($sPath);
        $images->resize(300,270);
        $images->save($dPath);
        
        return response()->json([
            'status'=>true,
            'image_id'=>$productimage->id,
            'imagepath'=>asset('uploads/product/small/'.$productimage->image),
            'message'=>'image saved successfully'

        ]);
    }

    public function destroy(Request $request){

        $productimage = ProductImages::find($request->id);

        //delete from folder
        File::delete(public_path('uploads/product/large/'.$productimage->image));
        File::delete(public_path('uploads/product/small/'.$productimage->image));

        $productimage->delete();
        return response()->json([
            'status'=>true,
            'message'=>'image deleted successfully'

        ]);
        
    }
}

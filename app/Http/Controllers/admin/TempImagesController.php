<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;

//require_once  './../vendor/autoload.php';

 
use Intervention\Image\ImageManager;

use Intervention\Image\Drivers\Gd\Driver;


class TempImagesController extends Controller
{
    public function create(Request $request){
        $image = $request->image;

        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $newName = time().".".$ext;

            $tempImage = new TempImage;
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path().'/temp', $newName);


            //generate thumbnail
            $sourcepath = public_path().'/temp/'.$newName;
            $destpath = public_path().'/temp/thumb/'.$newName;
            // create new image instance (800 x 600)
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcepath);
            $image->resize(300,270);
            $image->save($destpath);

            
        

            return response()->json([
                'status' =>true,
                'image_id'=>$tempImage->id,
                'imagepath'=>asset('/temp/thumb/'.$newName),
                'message'=>'image uploaded successfull'
            ]);
        }
    }
}

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
           // $image = ImageManager::imagick()->read($sourcepath);
            
            $image->scale(300,270);
            $image->save($destpath);
            //$destpath = public_path().'/temp/thumb/'.$newName;
           // create image manager with desired driver
            // 

            // // read image from file system
            // $image = $manager->read(public_path().'/temp/'.$newName);

            // // resize image proportionally to 300px width
            // $image->scale(width: 300);

            // // insert watermark
            // //$image->place('images/watermark.png');

            // // save modified image in new format 
            // $image->save(public_path().'/temp/thumb/'.$newName);


            return response()->json([
                'status' =>true,
                'image_id'=>$tempImage->id,
                'imagepath'=>asset('/temp/thumb/'.$newName),
                'message'=>'image uploaded successfull'
            ]);
        }
    }
}

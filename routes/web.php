<?php

use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;

use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\subCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProductSubCategoryController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\AdminLoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop',[ShopController::class,'index'])->name('front.shop');

Route::group(['prefix'=> 'admin'], function () {
    Route::group(['middleware'=> 'admin.guest'], function () {
        Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
 
    });
    Route::group(['middleware'=> 'admin.auth'], function () {
        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');
        //category
        Route::get('/category',[CategoryController::class,'index'])->name('category.index');
        Route::get('/category/create',[CategoryController::class,'create'])->name('category.create');
        Route::post('/category',[CategoryController::class,'store'])->name('category.store');
        Route::get('/category/{category}/edit',[CategoryController::class,'edit'])->name('category.edit');
        Route::put('/category/{category}',[CategoryController::class,'update'])->name('category.update');
        Route::delete('/category/{category}',[CategoryController::class,'destroy'])->name('category.delete');
  

        //sub_category
        Route::get('/sub-category/create',[subCategoryController::class,'create'])->name('sub-category.create');
        Route::post('/sub-category',[subCategoryController::class,'store'])->name('sub-category.store');
        Route::get('/sub-category',[subCategoryController::class,'index'])->name('sub-category.index');
        Route::get('/sub-category/{subCategory}/edit',[subCategoryController::class,'edit'])->name('sub-category.edit');
        Route::put('/sub-category/{subCategory}',[subCategoryController::class,'update'])->name('sub-category.update');
        Route::delete('/sub-category/{subCategory}',[subCategoryController::class,'destroy'])->name('sub-category.delete');


        //product
        Route::get('/product',[ProductController::class,'index'])->name('product.index');
        Route::get('/product/create',[ProductController::class,'create'])->name('product.create');
        Route::post('/product',[ProductController::class,'store'])->name('product.store');
        Route::get('/product/{product}/edit',[ProductController::class,'edit'])->name('product.edit');
        Route::put('/product/{product}',[ProductController::class,'update'])->name('product.update');
        Route::delete('/product/{product}',[ProductController::class,'destroy'])->name('product.delete');
        
        Route::post('/product-images/update',[ProductImageController::class,'update'])->name('product-images.update');
        Route::delete('/product-images',[ProductImageController::class,'destroy'])->name('product-images.destroy');



        Route::get('/product-subCategory',[ProductSubCategoryController::class,'index'])->name('product-subCategory.index');



        Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');



        Route::get('/getslug',function(Request $request) {

            $slug ="";
            if(!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status'=>true,
                'slug'=>$slug
            ]);


        })->name('getslug');

    });
});
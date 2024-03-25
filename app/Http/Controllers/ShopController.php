<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\admin\subCategory;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categoryslug = null, $subcategoryslug = null)
    {
        $categories = Category::orderBy('name', 'ASC')->with('sub_category')->where('status', 1)->get();

        $products = Product::where('status', 1);
        //Apply filters here
        $categoryselected = '';
        if (!empty ($categoryslug)) {
            $category = Category::where('slug', $categoryslug)->first();
            $products = Product::where('category_id', $category->id);
            $categoryselected = $category->id;

        }

        $subcategoryselected = '';
        if (!empty ($subcategoryslug)) {
            $subcategory = subCategory::where('slug', $subcategoryslug)->first();
            $products = Product::where('sub_category_id', $subcategory->id);
            $subcategoryselected = $subcategory->id;

        }

        if ($request->get('price_min') != '' && $request->get('price_max') != '') {

            if ($request->get('price_max') === 1000) {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 10000000]);
            } else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }


        }



        //$products= Product::orderBy('id','DESC')->where('status',1)->get();


        if ($request->get('sort') != '') {

            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('id', 'DESC');
            } elseif ($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price', 'ASC');
            } elseif ($request->get('sort') == 'price_desc') {
                $products = $products->orderBy('price', 'DESC');
            }

        } else {
            $products = $products->orderBy('id', 'DESC');
        }
        $products = $products->paginate(6);

        $data['categories'] = $categories;
        $data['products'] = $products;
        $data['categoryselected'] = $categoryselected;
        $data['subcategoryselected'] = $subcategoryselected;
        $data['pricemax'] = intval($request->get('price_max')) == 0 ? 1000 : intval($request->get('price_max'));
        $data['pricemin'] = intval($request->get('price_min'));
        $data['sort'] = $request->get('sort');
        return view('front.shop', $data);
    }

    public function product($slug)
    {

        $product = Product::where('slug', $slug)->with('product_images')->first();
        if ($product == null) {
            abort(404);
        }

        $relateproduct = [];
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relateproduct = Product::whereIn('id', $productArray)->with('product_images')->get();
        }

        $data['product'] = $product;
        $data["relatedproduct"] = $relateproduct;

        return view('front.product', $data);

    }
}

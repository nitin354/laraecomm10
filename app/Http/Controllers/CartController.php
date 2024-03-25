<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserAddress;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::with('product_images')->find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ]);
        }
        if (Cart::count() > 0) {
            //products found in cart
            //check this product is in cart
            $cartcontent = Cart::content();
            $productallreadyexist = false;

            foreach ($cartcontent as $item) {
                if ($item->id == $product->id) {
                    $productallreadyexist = true;
                }
            }

            if ($productallreadyexist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productimages' => (!empty($product->product_images) ? $product->product_images->first() : '')]);
                $status = true;
                $message = $product->title . ' added in cart';
            } else {
                $status = false;
                $message = 'Already in cart';
            }
        } else {
            //cart is empty
            Cart::add($product->id, $product->title, 1, $product->price, ['productimages' => (!empty($product->product_images) ? $product->product_images->first() : '')]);
            $status = true;
            $message = $product->title . ' added in cart';
        }
        // Cart::add('293ad', 'Product 1', 1, 9.99, ['size' => 'large']);
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }
    public function cart()
    {
        //dd(Cart::content());
        $cartcontent = Cart::content();
        $data['cartcontent'] = $cartcontent;
        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        //dd(Cart::content());

        $iteminfo = Cart::get($request->rowid);

        $product = Product::find($iteminfo->id);
        if ($product->track_qty == 'Yes') {
            if ($product->qty >= $request->qty) {
                Cart::update($request->rowid, $request->qty);
                $status = true;
                $message = "Cart Updated Successfully";
                session()->flash('success', $message);
            } else {
                $status = false;
                $message = 'Requested qty(' . $request->qty . ') not available in stock.';
                session()->flash('error', $message);
            }
        } else {
            Cart::update($request->rowid, $request->qty);
            $status = true;
            $message = "Cart Updated Successfully";
            session()->flash('success', $message);
        }


        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request)
    {
        $iteminfo = Cart::get($request->rowid);
        if ($iteminfo == null) {
            $message = "Item not found in cart";
            session()->flash('error', $message);
            return response()->json([
                'status' => false,
                'message' => $message
            ]);
        }
        $cartcontent = Cart::remove($request->rowid);
        $message = "Item removed Successfully";
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout()
    {
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        if (Auth::check() == false) {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }

            return redirect()->route('front.login');
        }
        $user = Auth::user();
        $customerAddress = UserAddress::where('user_id', $user->id)->get()->first();
        session()->forget('url.intended');

        return view('front.checkout', ['customerAddress' => $customerAddress]);
    }

    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        } else {
            $user = Auth::user();
            UserAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'mobile' => $request->mobile,
                    'apartment' => $request->apartment,
                    'notes' => $request->notes
                ]
            );

            if ($request->payment_method == 'cod') {
                $shipping = 0;
                $discount = 0;
                $subTotal = Cart::subtotal(2, '.', '');
                $grandTotal = $subTotal + $shipping;

                $order = new Order;
                $order->subtotal = $subTotal;
                $order->shipping = $shipping;
                $order->user_id = $user->id;
                $order->grand_total = $grandTotal;
                $order->first_name = $request->first_name;
                $order->last_name = $request->last_name;
                $order->email = $request->email;
                $order->address = $request->address;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zip = $request->zip;
                $order->mobile = $request->mobile;
                $order->apartment = $request->apartment;
                $order->notes = $request->notes;
                $order->save();


                foreach (Cart::content() as $item) {
                    $orderItem = new OrderItem;
                    $orderItem->product_id = $item->id;
                    $orderItem->order_id = $order->id;
                    $orderItem->name = $item->name;
                    $orderItem->qty = $item->qty;
                    $orderItem->price = $item->price;
                    $orderItem->total = $item->price * $item->qty;
                    $orderItem->save();
                }
                session()->flash('success', 'Thankyou For Your Order!');
                Cart::destroy();
                return response()->json([
                    'status' => true,
                    'order_id' => $order->id,
                    'message' => 'order saved Successfully!'
                ]);
            } else {
            }
        }
    }

    public function thankyou($id)
    {
        return view('front.thankyou', ['order_id' => $id]);
    }
}

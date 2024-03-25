<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }
    public function register()
    {
        return view('front.account.register');
    }

    public function saveuser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed',
        ]);

        if ($validate->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'You have been registerd successfully');
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validate->errors()

            ]);
        }
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

                //session()->flash('success', 'You have been registerd successfully');
                // if (session()->has('url.intended')) {
                //     return redirect(session()->get('url.intended'));
                // } else {
                //     return redirect()->route('front.profile');
                // }
                return redirect()->route('front.profile');
            } else {
                //session()->flash('error', 'Either Email/password is incorrect!');
                return redirect()->route('front.login')->with('error', 'Either Email/password is incorrect!');
            }
        } else {

            return redirect()->route('front.login')->withErrors($validator)->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        return view('front.account.profile');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('front.login')->with('success', 'You successfully logged out!');
    }
}

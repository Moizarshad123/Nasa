<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth, Mail;

class AdminController extends Controller
{
    public function dashboard() {

        return view('admin.dashboard');
    }
    
    public function login(Request $request) {

        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            $user = User::where('email', $request->input('email'))->first();
           
            if ($user != null) {

                if (Hash::check($request->input('password'), $user->password)) {
                    Auth::login($user);
                    return redirect(route('admin.dashboard'));
                    // if($user->role_id == 1) {
                    // } else {
                    //     return redirect('/');
                    // }
                    // return redirect(route('admin.dashboard'));
                } else {
                    return back()->withErrors(['password' => 'invalid email or password']);
                }
           
            } else {
                return back()->withErrors(['password' => 'invalid email or password']);
            }
        }
        return view('login');
    }
}

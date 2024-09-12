<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\TillOpen;


use Auth, Mail;

class AdminController extends Controller
{
    public function dashboard() {

        return view('admin.dashboard');
    }

    public function open_till(Request $request) {

        try {
            //code...
            TillOpen::create([
                "user_id" => auth()->user()->id,
                "type"    => "till_open",
                "amount"  => $request->amount,
                "date"    => date('Y-m-d'),
                "five_thousand" => $request->five_thousand,
                "one_thousand"  => $request->one_thousand,
                "five_hundred"  => $request->five_hundred,
                "one_hundred"   => $request->one_hundred,
                "fifty"  => $request->fifty,
                "twenty" => $request->twenty,
                "ten"    => $request->ten,
                "five"   => $request->five,
                "two"    => $request->two,
                "one"    => $request->aoneadfl,
                "notes"  => $request->notes,
            ]);
            return redirect()->back()->with("success", "Till open created");

        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function close_till(Request $request) {
        try {

            TillOpen::create([
                "user_id"       => auth()->user()->id,
                "type"          => "till_close",
                "amount"        => $request->amount,
                "date"          => date('Y-m-d'),
                "five_thousand" => $request->five_thousand,
                "one_thousand"  => $request->one_thousand,
                "five_hundred"  => $request->five_hundred,
                "one_hundred"   => $request->one_hundred,
                "fifty"         => $request->fifty,
                "twenty"        => $request->twenty,
                "ten"           => $request->ten,
                "five"          => $request->five,
                "two"           => $request->two,
                "one"           => $request->aoneadfl,
                "notes"         => $request->notes,
            ]);
            return redirect()->back()->with("success", "Till close created");

        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
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
            $user = User::where('email', $request->input('email'))->where('status', 1)->first();
           
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

    public function site_setting(Request $request) {

       
        $content = Setting::find(1);
        if ($request->method() == 'POST') {

            $content->urgent_amount_big = $request->input('urgent_amount_big');
            $content->expose_amount_big = $request->input('expose_amount_big');
            $content->media_amount_big  = $request->input('media_amount_big');
            $content->urgent_amount_small = $request->input('urgent_amount_small');
            $content->expose_amount_small = $request->input('expose_amount_small');
            $content->media_amount_small  = $request->input('media_amount_small');
            $content->save();

            return redirect()->back()->with('success', 'Site Setting Updated Successfully');
        }
        return view('admin.settings', compact('content'));
    }
}

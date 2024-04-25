<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Update;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(){
        return view('login.index');
    }

    public function loginCheck(Request $request){
        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials)){
            if(Auth::user()->role=='admin') return redirect()->route('admin.home')->with(['type' => 'success', 'message' =>'Welcome '. Auth::user()->name .' you are logged in successfully.']);;
        }
        else {
            return redirect()->route('login')->withErrors('Email address or password is incorrect!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function admin(){
            $usercount =User::count();
            $contactrequest =Contact::count();
            $updates =Update::count();
            $services= Activity::count();
            $users = User::all();
        return view('admin.home',compact('usercount','contactrequest','updates','services','users'));
    }

    public function RegisterUser(){
        return view('login.register');
    }
    public function register(Request $request){
        $email = User::where('email','=',$request->email)->first();
        if(true){ //$request->role=='user'
            if ($email==null) {
                $user = new User();
                $user->name = $request->name;
                $user->surname = $request->surname;
                $user->email = $request->email;
                $user->role = 'admin';
                $user->password = Hash::make($request->password);
                $user->save();
            }
            else{
                return redirect()->route('register.user')->withErrors('This email is registered. Please enter another email address.');
            }
        }
        return redirect()->route('login');
    }
}

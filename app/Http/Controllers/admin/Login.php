<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class Login extends Controller
{

    public function __construct()
    {
        // $this->middleware(['guest']);

    }

    public function index(){
        return view('admin.login');
    }
    public function login(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required',
        ]);
        $user=User::where('email','=',$request->email)->first();
        if(!$user){
            return back()
                ->with('error','Email-Address does not exist.');
        }
        if(auth()->attempt($request->only('email','password')))
        {
            if ($user->id) {
                $request->session()->put('loginId',$user->id);
                return redirect('admin/dashboard');
            }else{
                return redirect('admin/login');
            }
        }else{
            return redirect('admin/login')
                ->with('error','Email-Address Or Password Are Wrong.');
        }
    }
    public function logout(){
        if(Session()->has("loginId")){
            Session::pull('loginId');
            return redirect("admin/login");
        }
        // auth()->logout();

    }
}

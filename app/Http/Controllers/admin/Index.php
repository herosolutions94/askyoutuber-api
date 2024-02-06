<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Index extends Controller
{
    // private $data=array();
    public function __construct()
    {
        // $this->middleware(['guest']);
        $this->data['site_settings']=$this->getSiteSettings();
    }
    public function change_language(Request $request){
        if(Session()->has('site_lang')){
            if(session('site_lang') == 'en'){
                $request->session()->put('site_lang','es');
                return redirect('admin/dashboard');
            }
            else if(session('site_lang') == 'es'){
                $request->session()->put('site_lang','en');
                return redirect('admin/dashboard');
            }
            else{
                $request->session()->put('site_lang','en');
                return redirect('admin/dashboard');
            }
        }
        else{
            $request->session()->put('site_lang','en');
            return redirect('admin/dashboard');
        }
    }
    public function register(){
        return view('admin.register',$this->data);
    }
    public function store(Request $request){
        $this->validate($request,[
            'site_username'=>'required',
            'site_password'=>'required',
        ]);
        Admin::create([
            'site_username'=>$request->site_username,
            'site_password'=>Hash::make($request->site_password),
        ]);
        // auth()->attempt($request->only('site_username','site_password'));
        return redirect("admin/login");
    }
    public function admin_login(){
        return view('admin.login',$this->data);
    }
    public function login(Request $request){
        $this->validate($request,[
            'site_username'=>'required',
            'site_password'=>'required',
        ]);
        $admin=Admin::where('site_username','=',$request->site_username)->first();
        if(!$admin){
            return back()
                ->with('error','Username does not exist.');
        }
        else{
            if(Hash::check($request->site_password,$admin->site_password)){
                if ($admin->id) {
                    $request->session()->put('YoutuberLoginId',$admin->id);
                    $request->session()->put('site_lang','en');
                    return redirect('admin/dashboard');
                }else{
                    return redirect('admin/login');
                }
            }
            else{
                return redirect('admin/login')
                ->with('error','Password is not correct!');
            }
        }

    }
    public function logout(){
        if(Session()->has("YoutuberLoginId")){
            Session::pull('YoutuberLoginId');
            return redirect("admin/login");
        }
        // auth()->logout();

    }
}

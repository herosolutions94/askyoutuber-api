<?php

namespace App\Http\Controllers\admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class Dashboard extends Controller
{

    public function index(){
        return view('admin.dashboard',$this->data);

    }
    public function settings(){
        return view('admin.site_settings',$this->data);
    }
    public function change_password(Request $request){
        if(Session()->has('site_lang')){
            if(session('site_lang') == 'es'){
                $settings_id=2;
            }
            else{
                $settings_id=1;
            }
        }
        else{
            $settings_id=1;
        }
        $admin=Admin::find($settings_id);
        $input = $request->all();
        if($input){
            $this->validate($request, [
                'current_password'     => 'required',
                'new_password'     => 'required',
                'confirm_password' => 'required|same:new_password',
            ]);
            if(Hash::check($input['current_password'],$admin->site_password)){
                $admin->site_password=Hash::make($input['new_password']);
                $admin->save();
                return redirect('admin/change-password')
                ->with('success','Updated Successfully');
            }
            else{
                return redirect('admin/change-password')
                ->with('error','Current Password is not right!');
            }
        }
        $this->data['vh_100']=true;
        return view('admin.change_password',$this->data);
    }

    public function settings_update(Request $request){
        if(Session()->has('site_lang')){
            if(session('site_lang') == 'es'){
                $settings_id=2;
            }
            else{
                $settings_id=1;
            }
        }
        else{
            $settings_id=1;
        }
        $admin=Admin::find($settings_id);
        if($request->hasFile('site_logo')){
            $request->validate([
                'site_logo' => 'mimes:png,jpg,jpeg,svg,gif|max:2048'
            ]);
            $site_logo=$request->file('site_logo')->store('public/images/');
            if(!empty($site_logo)){
                if(!empty($this->data['site_settings']->site_logo)){
                    removeImage("images/".$this->data['site_settings']->site_logo);
                }

                $admin->site_logo=basename($site_logo);
            }

        }
        if($request->hasFile('site_icon')){
            $request->validate([
                'site_icon' => 'mimes:png,jpg,jpeg,svg,gif,ico|max:2048'
            ]);
            $site_icon=$request->file('site_icon')->store('public/images/');
            if(!empty($site_icon)){
                if(!empty($this->data['site_settings']->site_icon)){
                    removeImage("images/".$this->data['site_settings']->site_icon);
                }
                $admin->site_icon=basename($site_icon);
            }
        }
        if($request->hasFile('site_thumb')){
            $request->validate([
                'site_thumb' => 'mimes:png,jpg,jpeg,svg,gif|max:2048'
            ]);
            $site_thumb=$request->file('site_thumb')->store('public/images/');
            if(!empty($site_thumb)){
                if(!empty($this->data['site_settings']->site_thumb)){
                    removeImage("images/".$this->data['site_settings']->site_thumb);
                }
                $admin->site_thumb=basename($site_thumb);
            }
        }


        if($request->site_stripe_type){
            $site_stripe_type=1;
        }
        else{
            $site_stripe_type=0;
        }
        $admin->site_domain=$request->site_domain;
        $admin->site_name=$request->site_name;
        $admin->site_phone=$request->site_phone;
        $admin->site_email=$request->site_email;
        $admin->site_noreply_email=$request->site_noreply_email;
        $admin->site_address=$request->site_address;
        $admin->site_about=$request->site_about;
        $admin->site_copyright=$request->site_copyright;
        $admin->site_meta_desc=$request->site_meta_desc;
        $admin->site_meta_keyword=$request->site_meta_keyword;
        $admin->site_facebook=$request->site_facebook;
        $admin->site_twitter=$request->site_twitter;
        $admin->site_pinterest=$request->site_pinterest;
        $admin->site_linkedin=$request->site_linkedin;
        $admin->site_instagram=$request->site_instagram;
        $admin->site_stripe_type=$site_stripe_type;
        $admin->site_stripe_testing_api_key=$request->site_stripe_testing_api_key;
        $admin->site_stripe_testing_secret_key=$request->site_stripe_testing_secret_key;
        $admin->site_stripe_live_api_key=$request->site_stripe_live_api_key;
        $admin->site_stripe_live_secret_key=$request->site_stripe_live_secret_key;
        // pr($admin);


        $admin->save();
        return redirect('admin/site_settings')
                ->with('success','Updated Successfully');

    }

}

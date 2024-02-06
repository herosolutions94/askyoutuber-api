<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Account extends Controller
{
    public function dashboard(Request $request){
        $site_language = $request->header('site_lang');
        $output['site_settings']=(object)[];
        $output['site_settings']=$this->getSiteSettings('en');
        $output['site_settings']->en=$this->getSiteSettings('en');
        $output['site_settings']->es=$this->getSiteSettings('es');
        exit(json_encode($output));
    }
    public function profile(Request $request){
        $output=array();
        $input=$request->all();
        if($input['token']){
            $output['site_settings']=$this->getSiteSettings($input['site_lang']);
            $output['member']=$this->authenticate_verify_token($input['token']);
            if(!empty($output['member'])){
                $output['otp_deadline']=$output['member']->otp_expire;
                $output['mem_image']=get_site_image_src('members', !empty($output['member']->mem_image) ? $output['member']->mem_image : '');
                $output['mem_name']=$output['member']->mem_fname." ".$output['member']->mem_lname;
            }
            else{
                $output['member']=null;
            }
            
        }
        exit(json_encode($output));
    }
    public function update_password(Request $request){
        $res=array();
        $res['status']=0;
        $input=$request->all();
        // pr($input);
        if($input['token']){
            $member=$this->authenticate_verify_token($input['token']);
            if(!empty($member)){
                $input = $request->all();
                $request_data = [
                    'old_password'     => 'required',
                    'new_password'     => 'required',
                    'confirm_password' => 'required|same:new_password',
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    if($member->mem_password==md5($input['old_password'])){
                        $member->mem_password=md5($input['new_password']);
                        $member->update();
                        $res['msg']="Password updated successfully!";
                        $res['status']=1;
                    }
                    else{
                        $res['msg']='Old password does not match';
                    }
                }
            }
            else{
                $res['msg']='User is invalid!';
            }
        }
        else{
            $res['msg']='User is invalid!';
        }

        exit(json_encode($res));
    }
    public function become_youtuber_request(Request $request){
        $res=array();
        $res['status']=0;
        $input=$request->all();
        // pr($input);
        if($input['token']){
            $member=$this->authenticate_verify_token($input['token']);
            if(!empty($member)){
                if($member->mem_type=='youtuber'){
                    $res['msg']='You are already a youtuber!';
                    exit(json_encode($res));
                }
                $member->mem_type='youtuber';
                $member->update();
                $res['status']=1;
                $res['mem_type']='youtuber';
                $res['msg']='Congratulations! your status is changed to youtuber successfully!';
            }
            else{
                $res['msg']='Please login to make this request!';
            }
        }
        else{
            $res['msg']='Please login to make this request!';
        }

        exit(json_encode($res));
    }
    public function update_profile(Request $request){
        $res=array();
        $res['status']=0;
        $input=$request->all();
        // pr($input);
        if($input['token']){
            $member=$this->authenticate_verify_token($input['token']);
            if(!empty($member)){
                $request_data = [
                    'fname' => 'required',
                    'phone' => 'required',
                    'lname' => 'required',
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors();
                }
                else{
                    $member->mem_fname=$input['fname'];
                    $member->mem_lname=$input['lname'];
                    $member->mem_phone=$input['phone'];
                    if($member->mem_type=='youtuber'){
                        $member->mem_bio=$input['bio'];
                    }
                    $member->update();
                    $res['status']=1;
                    $res['msg']='Updated successfully!';
                    $res['member']=$member;
                    $res['otp_deadline']=$res['member']->otp_expire;
                    $res['mem_name']=$res['member']->mem_fname." ".$res['member']->mem_lname;
                    $res['mem_image']=get_site_image_src('members', !empty($res['member']->mem_image) ? $res['member']->mem_image : '');
                }
                
            }
            else{
                $res['msg']='User is invalid!';
            }
        }
        exit(json_encode($res));
    }
    public function resend_otp_code(Request $request){
        $res=array();
        $res['status']=0;
        $input=$request->all();
        if($input['token']){
            $member=$this->authenticate_verify_token($input['token']);
            if(!empty($member)){
                $member->otp_expire=date('Y-m-d H:i:s', strtotime('+3 minute'));
                $member->otp=random_int(100000, 999999);
                $member->update();
                $res['msg']='Code resend successfully!';
                $res['otp_deadline']=$member->otp_expire;
                $res['status']=1;
            }
            else{
                $res['msg']='User is invalid!';
            }
        }
        exit(json_encode($res));
    }
    public function verify_otp_code(Request $request){
        $res=array();
        $res['status']=0;
        $input=$request->all();
        if($input['token']){
            $member=$this->authenticate_verify_token($input['token']);
            if(!empty($member)){
                if(!empty($input['otp'])){
                    if($input['otp']!=$member->otp){
                        $res['msg']='OTP is not valid!';
                        exit(json_encode($res));
                    }
                    if(date('Y-m-d H:i:s') > date('Y-m-d H:i:s',strtotime($member->otp_expire))){
                        $res['msg']='OTP is expired. Please generate a note OTP!';
                        exit(json_encode($res));
                    }
                    $member->otp='';
                    $member->mem_verified=1;
                    $member->mem_email_verified=1;
                    $member->update();
                    $res['msg']='Verified successfully!';
                    // $res['member']=$member;
                    $res['status']=1;
                    $res['mem_type']=$member->mem_type;
                }
                else{
                    $res['msg']='OTP is required!';
                }
            }
            else{
                $res['msg']='User is invalid!';
            }
        }
        exit(json_encode($res));
    }
}

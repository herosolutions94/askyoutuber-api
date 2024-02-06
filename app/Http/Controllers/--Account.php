<?php

namespace App\Http\Controllers;

use App\Models\Member_model;
use App\Models\Member_questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class Account extends Controller
{
    public function updateNotificationStatus(Request $request){
        $res=array();
        $res['status']=1;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if(!empty($member)){
            $notifications=DB::table('notifications')->where(['mem_id'=> $member->id,'status'=>0])->get();

            if($notifications->count() > 0){
                foreach($notifications as $notify){
                    DB::table('notifications')->where('id', $notify->id)->update(array('status' => 1));
                }
                $res['status']=1;
                $res['unread']=DB::table('notifications')->where(['mem_id'=> $member->id,'status'=>0])->get()->count();
            }
        }
        else{
            $res['member']=null;
        }

        exit(json_encode($res));
    }
    public function update_security_question(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if(!empty($member)){
            $member_row=Member_model::where(['id' => $member->id])->get()->first();
            $member_questions=Member_questions::where(['mem_id' => $member->id])->get()->first();
            $input = $request->all();
            
            $questions=array(
                'question1'=>$input['question1'],
                'question2'=>$input['question2'],
                'question3'=>$input['question3'],
            );
            $answers=array(
                'answer1'=>$input['answer1'],
                'answer2'=>$input['answer2'],
                'answer3'=>$input['answer3'],
            );

            if(!empty($member_questions)){
                if(empty($input['password'])){
                    $res['msg']='Please enter your password to update security questions!';
                    $res['status']=0;
                    exit(json_encode($res));
                }
                if(!empty($input['password'])){
                    $member_pass_row=Member_model::where(['mem_email' => $member->mem_email,'mem_password'=>md5($input['password'])])->get()->first();
                    if(empty($member_pass_row)){
                        $res['msg']='Password is not correct. Please enter accurate password!';
                        $res['status']=0;
                        exit(json_encode($res));
                    }
                }
                $mem_answers=json_decode($member_questions->answer);
                
                $answers=array(
                    'answer1'=>($input['answer1']!='XXXXXXX') ? $input['answer1'] : $mem_answers->answer1,
                    'answer2'=>($input['answer2']!='XXXXXXX') ? $input['answer2'] : $mem_answers->answer2,
                    'answer3'=>($input['answer3']!='XXXXXXX') ? $input['answer3'] : $mem_answers->answer3,
                );
                // $res['answers']=$answers;
                // exit(json_encode($res));
                $member_questions->mem_id=$member->id;
                $member_questions->question=json_encode($questions);
                $member_questions->answer=json_encode($answers);
                $member_questions->status=0;
                $member_questions->update();
                $res['msg']='Questions updated successfully!';
            }
            else{
                $data=array(
                    'mem_id'=>$member->id,
                    'question'=>json_encode($questions),
                    'answer'=>json_encode($answers),
                    'status'=>0
                );
                $res['data']=$data;
                Member_questions::create($data);
                $res['msg']='Questions added successfully';
            }
            $res['status']=1;

        }
        else{
            $res['member']=null;
        }

        exit(json_encode($res));
    }
    public function verification($token){
        $res=array();
        // $res['status']=0;
        $member=$this->authenticate_verify_email_token($token);
        // pr($member);
        // $res['member']=$member;
        if(!empty($member)){
            $email=$member->email_change;
            $old_email=$member->mem_email;
            if($member->mem_email_verified===0){
                $memberRow=Member_model::where(['id' => $member->id])->get()->first();
                $memberRow->otp='';
                if(!empty($memberRow->email_change)){
                    $memberRow->email_change='';
                    $memberRow->mem_email=$email;
                }
                
                $memberRow->mem_email_verified=1;
                if($memberRow->mem_phone_verified===1){
                    $memberRow->mem_verified=1;
                    $memberRow->otp_phone='';
                }
                $memberRow->update();
                $email_welcome_data=array(
                    'email_to'=>$memberRow->mem_email,
                    'email_to_name'=>$memberRow->mem_fname,
                    'email_from'=>'noreply@liveloftus.com',
                    'email_from_name'=>$this->data['site_settings']->site_name,
                    'subject'=>'Welcome to Loftus!',
                    // 'code'=>$data['otp'],
                );
                send_email($email_welcome_data,'welcome');
            }

            $res['status']=1;
        }
        else{
            $res['status']=0;
        }
        exit(json_encode($res));
    }
    public function verified_email_verification($token){
        $res=array();
        $res['status']=0;

        $member=$this->authenticate_verify_email_token($token);
        // $res['member']=$member;
        // exit(json_encode($res));
        if(!empty($member)){
            $email=$member->email_change;
            $old_email=$member->mem_email;
                $memberRow=Member_model::where(['id' => $member->id])->get()->first();
                if(!empty($email)){
                   $memberRow->email_change='';
                    $memberRow->mem_email=$email;
                    $memberRow->update(); 
                    // $change_email_data=array(
                    //     'email_to'=>$email,
                    //     'email_to_name'=>$memberRow->mem_fname,
                    //     'email_from'=>'noreply@liveloftus.com',
                    //     'email_from_name'=>$this->data['site_settings']->site_name,
                    //     'subject'=>'Email Updated',
                    //     'new_email'=>true,
                    // );
                    // send_email($change_email_data,'verified_email_change');
                    $old_email_data=array(
                        'mem_email'=>$email,
                        'email_to'=>$old_email,
                        'email_to_name'=>$memberRow->mem_fname,
                        'email_from'=>'noreply@liveloftus.com',
                        'email_from_name'=>$this->data['site_settings']->site_name,
                        'subject'=>'Email Updated',
                        'old_email'=>true,
                    );
                    send_email($old_email_data,'verified_email_change');
                }
                
                


            $res['status']=1;
        }
        else{
            $res['status']=0;
        }
        exit(json_encode($res));
    }
    public function change_email_confirmation($token){
        $res=array();
        $res['status']=0;

        $member=$this->authenticate_verify_email_token($token);
        // $res['member']=$member;
        
        if(!empty($member)){
            $email=$member->email_change;
            // $old_email=$member->mem_email;
               $token=$member->id."-".$member->mem_email."-".$member->mem_type."-".rand(99,999);
            $userToken=encrypt_string($token);
            $date_to_be=date("Y-m-d H:i:s");
                    $added_date=date("Y-m-d H:i:s",strtotime('+6 hours', strtotime($date_to_be)));
            $token_array=array(
                'mem_type'=>$member->mem_type,
                'token'=>$userToken,
                'mem_id'=>$member->id,
                'expiry_date'=>$added_date,
            );
            DB::table('tokens')->insert($token_array);
                
               $email_data=array(
                        'email_to'=>$email,
                        'email_to_name'=>$member->mem_fname,
                        'email_from'=>'noreply@liveloftus.com',
                        'email_from_name'=>$this->data['site_settings']->site_name,
                        'subject'=>'New Email Verification',
                        'link'=>config('app.react_url')."/verified-email-verification/".$userToken,
                        // 'code'=>$data['otp'],
                    );
                    $email=send_email($email_data,'change_email_confirm');


            $res['status']=1;
        }
        else{
            $res['status']=0;
        }
        exit(json_encode($res));
    }
    public function resend_email(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if(!empty($member)){
            $memberRow=Member_model::where(['id' => $member->id])->get()->first();
            $otp=random_int(100000, 999999);
            $memberRow->otp=$otp;
            $memberRow->update();
            $token=$memberRow->id."-".$memberRow->mem_email."-".$memberRow->mem_type."-".rand(99,999);
            $userToken=encrypt_string($token);
            $token_array=array(
                'mem_type'=>$memberRow->mem_type,
                'token'=>$userToken,
                'mem_id'=>$memberRow->id,
                'expiry_date'=>date("Y-m-d", strtotime("6 months")),
            );
            DB::table('tokens')->insert($token_array);
            $email_data=array(
                'email_to'=>$memberRow->mem_email,
                'email_to_name'=>$memberRow->mem_fname,
                'email_from'=>'noreply@liveloftus.com',
                'email_from_name'=>$this->data['site_settings']->site_name,
                'subject'=>'Email Verification',
                'link'=>config('app.react_url')."/verification/".$userToken,
                // 'code'=>$data['otp'],
            );
            $email=send_email($email_data,'account');
            if($email){
                $res['msg']="Verification email has been sent with verification link to your email.";
                $res['status']=1;
            }
            else{
                $res['msg']="Email could not be sent!";
            }

        }
        else{
            $res['member']=null;
        }

        exit(json_encode($res));
    }
    public function resend_otp_code(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);

        if(!empty($member)){
            $memberRow=Member_model::where(['id' => $member->id])->get()->first();
            $otp_phone=random_int(100000, 999999);
            $otp_expire=date('Y-m-d H:i:s', strtotime('+3 minute'));
            $memberRow->otp_phone=$otp_phone;
            $memberRow->otp_expire=$otp_expire;
            $memberRow->update();

            // $phone_data=array(
            //     'message'=>'You have successfully registered on Live Loft Us. To activate your Liveloftus account, you must you verify your email address. Your account will not be created until your email address is verified. You can use this code to activate your account:'.$otp_phone,
            //     'receiver'=>$memberRow->mem_phone
            // );
            // // $res['phone_data']=$phone_data;
            // // exit(json_encode($res));
            // $sms=sendMsg($phone_data);
             $otp_req=sendOTP($memberRow->mem_phone,$otp_phone);

            
            if(!empty($otp_req)){
                $res['msg']="New OTP has been sent to your phone number.";
                $res['expired_date']=format_date($otp_expire,'Y-m-d H:i:s');
                $res['status']=1;
            }
            else{
                $res['msg']="OTP could not be sent!";
            }

        }
        else{
            $res['member']=null;
        }

        exit(json_encode($res));
    }
    public function resend_otp_code_to_new_phone(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);

        if(!empty($member)){
            $memberRow=Member_model::where(['id' => $member->id])->get()->first();
            $otp_phone=random_int(100000, 999999);
            $otp_expire=date('Y-m-d H:i:s', strtotime('+3 minute'));
            $memberRow->otp_phone=$otp_phone;
            $memberRow->otp_expire=$otp_expire;
            $memberRow->update();

            // $phone_data=array(
            //     'message'=>'You have successfully registered on Live Loft Us. To activate your Liveloftus account, you must you verify your email address. Your account will not be created until your email address is verified. You can use this code to activate your account:'.$otp_phone,
            //     'receiver'=>$memberRow->mem_phone
            // );
            // // $res['phone_data']=$phone_data;
            // // exit(json_encode($res));
            // $sms=sendMsg($phone_data);
             $otp_req=sendOTP($memberRow->phone_change,$otp_phone);

            
            if(!empty($otp_req)){
                $res['msg']="New OTP has been sent to your new phone number.";
                $res['expired_date']=format_date($otp_expire,'Y-m-d H:i:s');
                $res['status']=1;
            }
            else{
                $res['msg']="OTP could not be sent!";
            }

        }
        else{
            $res['member']=null;
        }

        exit(json_encode($res));
    }
    public function verified_otp_check(Request $request){
        $res=array();
        $res['status']=0;
        $res['email_verify']=0;
        $input = $request->all();
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_email_token($header);
        // $res['input']=$input;
        // exit(json_encode($res));
        if(!empty($member)){
            $phone=$member->phone_change;
            $old_phone=$member->mem_phone;
            if($input){
                // $verifyMember=Member_model::where(['otp_phone' => $input,'id'=>$member->id])->get()->first();;
                // if(!empty($verifyMember)){

                    if(strtotime(date('Y-m-d H:i:s')) > strtotime(date('Y-m-d H:i:s',strtotime($member->otp_expire)))){
                        $res['msg']="Your OTP has expired. Please resend a new OTP to verify your phone number. ";
                        $res['status']=0;
                        exit(json_encode($res));
                    }
                    // $res['input']=$input[0];
                    // $res['phone']=$phone;
                    // $res['otp_phone']=$member->otp_phone;
                    // exit(json_encode($res));
                    $member_row=Member_model::find($member->id);
                    if(!empty($phone)){
                        $otp_verify=verifyOtp($input[0],$phone,$member->otp_phone);
                        $member_row->mem_phone=$phone;
                        $member_row->phone_change='';
                    }
                    else{
                        $otp_verify=verifyOtp($input[0],$member->mem_phone,$member->otp_phone);
                    }

                    if($otp_verify==true || $otp_verify==1){
                        
                        $member_row->otp_phone='';
                        $member_row->update();
                        $phone_data=array(
                            'message'=>'You have changed your phone number on Live Loft Us. Your new phone number is'.$phone,
                            'receiver'=>$phone
                        );
                        $sms=sendMsg($phone_data);
                        
                        $email_data=array(
                            'email_to'=>$member_row->mem_email,
                            'new_phone'=>$phone,
                            'old_phone'=>$old_phone,
                            'email_to_name'=>$member_row->mem_fname,
                            'email_from'=>'noreply@liveloftus.com',
                            'email_from_name'=>$this->data['site_settings']->site_name,
                            'subject'=>'Phone Number Updated',
                            'phone_changed'=>1,
                        );
                        $email=send_email($email_data,'phone_change');
                        $res['status']=1;
                        $res['msg']='Your phone number has been successfully updated!';
                    }
                    else{
                         $res['otp_verify']=$otp_verify;
                         $res['msg']='The OTP you entered is incorrect. Please enter the correct OTP or resend a new OTP. ';
                    }
                // }
                // else{
                //     $res['status']=0;
                //     $res['msg']='OTP is not correct!';
                // }


            }
            else{
                $res['msg']='No OTP found';
            }
        }
        else{
            $res['status']=0;
            $res['msg']='Something went wrong!';
        }

        exit(json_encode($res));
    }
    public function send_otp_to_phone_verification(Request $request){
        $res=array();
        $res['status']=0;
        $res['email_verify']=0;
        $input = $request->all();
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_email_token($header);
        // $res['input']=$input;
        // exit(json_encode($res));
        if(!empty($member)){
            $phone=$member->phone_change;
            if($input){
                $verifyMember=Member_model::where(['otp_phone' => $input,'id'=>$member->id])->get()->first();;
                if(!empty($verifyMember)){
                    if(strtotime(date('Y-m-d H:i:s')) > strtotime(date('Y-m-d H:i:s',strtotime($verifyMember->otp_expire)))){
                        $res['msg']="Your OTP has expired. Please resend a new OTP to verify your phone number.";
                        $res['status']=0;
                        exit(json_encode($res));
                    }
                    $member_row=Member_model::find($verifyMember->id);
                    
                    $otp_phone=random_int(100000, 999999);
                    $otp_expire=date('Y-m-d H:i:s', strtotime('+3 minute'));
                    $member_row->otp_phone=$otp_phone;
                    $member_row->otp_expire=$otp_expire;
                    $member_row->update();
                    // $phone_data=array(
                    //     'message'=>'Your number has been added to Liveloftus. If you want to use this number for liveloftus account, use this OTP to verify your phone number. OTP:'.$otp_phone,
                    //     'receiver'=>$phone
                    // );
                    // $sms=sendMsg($phone_data);
                    $otp_req=sendOTP($phone,$otp_phone);
                    if(!empty($otp_req)){
                        $res['status']=1;
                        $res['expired_date']=format_date($otp_expire,'Y-m-d H:i:s');
                        $res['msg']='OTP has been sent to your requested phone number for verification.';
                    }
                    else{
                        $res['status']=1;
                        $res['expired_date']=format_date($otp_expire,'Y-m-d H:i:s');
                        $res['msg']='OTP can not be sent to your requested phone number.';
                    }
                }
                else{
                    $res['status']=0;
                    $res['msg']='OTP is not correct!';
                }


            }
        }
        else{
            $res['status']=0;
            $res['msg']='Something went wrong!';
        }

        exit(json_encode($res));
    }
    public function resend_phone_otp(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if (!empty($member)) {
            $input = $request->all();
            if ($input) {
                $request_data = [
                    'phone' => 'required|unique:members,mem_phone',
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']=convertArrayMessageToString($validator->errors()->all());
                } else {
                    $memberRow=Member_model::where(['id' => $member->id])->get()->first();
                    $otp_expire=date('Y-m-d H:i:s', strtotime('+3 minute'));
                    $otp_phone=random_int(100000, 999999);
                    $memberRow->otp_phone=$otp_phone;
                    // $memberRow->phone_change=$input['phone'];
                    $memberRow->mem_phone=$input['phone'];
                    $memberRow->otp_expire=$otp_expire;
                    $memberRow->update();
                    // $phone_data=array(
                    //     'message'=>'You have successfully registered on Live Loft Us. To activate your Liveloftus account, you must you verify your email address. Your account will not be created until your email address is verified. You can use this code to activate your account:'.$otp_phone,
                    //     'receiver'=>$input['phone']
                    // );
                    // $sms=sendMsg($phone_data);
                    $otp_req=sendOTP($input['phone'],$otp_phone);
                    if(!empty($otp_req)){
                        $res['msg']="An OTP was sent to your new phone number. ";
                        $res['status']=1;

                    $res['expired_date']=format_date($otp_expire,'Y-m-d H:i:s');
                    } else {
                        $res['msg']="Message could not be sent!";
                    }
                }
            } else {
                $res['member']=null;
            }

            exit(json_encode($res));
        }
    }
    public function change_verified_email(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if (!empty($member)) {
            $input = $request->all();
            if ($input) {
                $request_data = [
                    'email' => 'required',
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']=convertArrayMessageToString($validator->errors()->all());
                } else {
                    $emailCount=Member_model::whereNotIn('id',[$member->id])->where(['mem_email'=>$input['email']])->get()->count();
                    if($emailCount > 0){
                        $res['msg']='The email address you specified is already in use. Do you already have an account?';
                        exit(json_encode($res));
                    }
                    if($member->mem_email==$input['email']){
                        $res['msg']='This is your current email. Please add another email to change!';
                        exit(json_encode($res));
                    }
                    $memberRow=Member_model::where(['id' => $member->id])->get()->first();
                    $otp=random_int(100000, 999999);
                    $memberRow->otp=$otp;
                    $memberRow->email_change=$input['email'];
                    $memberRow->update();
                    $token=$memberRow->id."-".$memberRow->mem_email."-".$memberRow->mem_type."-".rand(99,999);
                    $userToken=encrypt_string($token);
                    $date_to_be=date("Y-m-d H:i:s");
                    $added_date=date("Y-m-d H:i:s",strtotime('+6 hours', strtotime($date_to_be)));
                    $token_array=array(
                        'mem_type'=>$memberRow->mem_type,
                        'token'=>$userToken,
                        'mem_id'=>$memberRow->id,
                        'expiry_date'=>$added_date,
                    );
                    // pr($token_array);
                    DB::table('tokens')->insert($token_array);

                    // $email_data=array(
                    //     'email_to'=>$memberRow->mem_email,
                    //     'email_change'=>$input['email'],
                    //     'email_to_name'=>$memberRow->mem_fname,
                    //     'email_from'=>'noreply@liveloftus.com',
                    //     'email_from_name'=>$this->data['site_settings']->site_name,
                    //     'subject'=>'Request to change email!',
                    //     // 'link'=>config('app.react_url')."/verified-email-verification/".$userToken,
                    //     'link'=>config('app.react_url')."/change-email-confirmation/".$userToken,
                    //     // 'code'=>$data['otp'],
                    // );
                    // $email=send_email($email_data,'emailVerifyChange');
                    $email_data=array(
                        'email_to'=>$memberRow->mem_email,
                        'old_email'=>$memberRow->mem_email,
                        'email_change'=>$input['email'],
                        'email_to_name'=>$memberRow->mem_fname,
                        'email_from'=>'noreply@liveloftus.com',
                        'email_from_name'=>$this->data['site_settings']->site_name,
                        'subject'=>'Email Update Request ',
                        'link'=>config('app.react_url')."/change-email-confirmation/".$userToken,
                        // 'code'=>$data['otp'],
                    );
                    $email=send_email($email_data,'emailChange');

                    if($email){
                        $res['msg']="A verification link has been sent to your existing email address. Please click on the link to update your email address.";
                        $res['status']=1;
                    }
                    else{
                        $res['msg']="Email could not be sent!";
                    }
                }
            } else {
                $res['member']=null;
            }

            exit(json_encode($res));
        }
    }
    public function resend_otp_code_to_email(Request $request){
        $res=array();
        $res['status']=0;
        
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        
        if(!empty($member)){
            $memberRow=Member_model::where(['id' => $member->id])->get()->first();
            $otp_phone=random_int(100000, 999999);
            $otp_expire=date('Y-m-d H:i:s', strtotime('+3 minute'));
            $memberRow->otp_phone=$otp_phone;
            $memberRow->otp_expire=$otp_expire;
            $memberRow->update();
            $email_data=array(
                'email_to'=>$memberRow->mem_email,
                'old_phone'=>$memberRow->mem_phone,
                'phone_change'=>$memberRow->phone_change,
                'email_to_name'=>$memberRow->mem_fname,
                'email_from'=>'noreply@liveloftus.com',
                'email_from_name'=>$this->data['site_settings']->site_name,
                'subject'=>'Phone Number Update Request',
                'code'=>$otp_phone,
                'send_otp'=>1,
            );
            $email=send_email($email_data,'phone_change');
            if($email){
                $res['msg']="New OTP has been sent to your email.";
                $res['expired_date']=format_date($otp_expire,'Y-m-d H:i:s');
                $res['status']=1;
            }
            else{
                $res['msg']="Email could not be sent!";
            }

        }
        else{
            $res['member']=null;
        }

        exit(json_encode($res));
    }
    public function change_verified_phone(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if (!empty($member)) {
            $input = $request->all();
            if ($input) {
                $request_data = [
                    'phone' => 'required',
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']=convertArrayMessageToString($validator->errors()->all());
                } else {
                    $phoneCount=Member_model::whereNotIn('id',[$member->id])->where(['mem_phone'=>$input['phone']])->get()->count();
                    if($phoneCount > 0){
                        $res['msg']='Phone Number is already in use';
                        exit(json_encode($res));
                    }
                    if($member->mem_phone==$input['phone']){
                        $res['msg']='This is your current phone number. Please add another phone number to change!';
                        exit(json_encode($res));
                    }
                    $memberRow=Member_model::where(['id' => $member->id])->get()->first();
                    $otp_expire=date('Y-m-d H:i:s', strtotime('+3 minute'));
                    $otp=random_int(100000, 999999);
                    $memberRow->otp_phone=$otp;
                    $memberRow->phone_change=$input['phone'];

                    $memberRow->otp_expire=$otp_expire;
                    $memberRow->update();
                    $token=$memberRow->id."-".$memberRow->mem_email."-".$memberRow->mem_type."-".rand(99,999);
                    $userToken=encrypt_string($token);
                    $date_to_be=date("Y-m-d H:i:s");
                    $added_date=date("Y-m-d H:i:s",strtotime('+6 hours', strtotime($date_to_be)));
                    $token_array=array(
                        'mem_type'=>$memberRow->mem_type,
                        'token'=>$userToken,
                        'mem_id'=>$memberRow->id,
                        'expiry_date'=>$added_date,
                    );
                    // pr($token_array);
                    DB::table('tokens')->insert($token_array);

                    $email_data=array(
                        'email_to'=>$memberRow->mem_email,
                        'old_phone'=>$memberRow->mem_phone,
                        'phone_change'=>$input['phone'],
                        'email_to_name'=>$memberRow->mem_fname,
                        'email_from'=>'noreply@liveloftus.com',
                        'email_from_name'=>$this->data['site_settings']->site_name,
                        'subject'=>'Phone Number Update Request',
                        'code'=>$otp,
                        'send_otp'=>1,
                    );
                    $email=send_email($email_data,'phone_change');

                    if($email){
                        $res['msg']="A verification OTP has been sent to your email.";
                        $res['status']=1;
                        $res['expired_date']=format_date($otp_expire,'Y-m-d H:i:s');
                    }
                    else{
                        $res['msg']="Email could not be sent!";
                    }
                }
            } else {
                $res['member']=null;
            }

            exit(json_encode($res));
        }
    }
    public function change_email(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if (!empty($member)) {
            $old_email=$member->mem_email;
            $input = $request->all();
            if ($input) {
                $request_data = [
                    'email' => 'required|unique:members,mem_email',
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']=convertArrayMessageToString($validator->errors()->all());
                } else {
                    $memberRow=Member_model::where(['id' => $member->id])->get()->first();
                    $otp=random_int(100000, 999999);
                    $memberRow->otp=$otp;
                    $memberRow->mem_email=$input['email'];
                    $memberRow->update();
                    $token=$memberRow->id."-".$memberRow->mem_email."-".$memberRow->mem_type."-".rand(99,999);
                    $userToken=encrypt_string($token);
                    $date_to_be=date("Y-m-d H:i:s");
                    $added_date=date("Y-m-d H:i:s",strtotime('+6 hours', strtotime($date_to_be)));
                    $token_array=array(
                        'mem_type'=>$memberRow->mem_type,
                        'token'=>$userToken,
                        'mem_id'=>$memberRow->id,
                        'expiry_date'=>$added_date,
                    );
                    DB::table('tokens')->insert($token_array);

                    // $email_data=array(
                    //     'email_to'=>$input['email'],
                    //     'old_email'=>$old_email,
                    //     'email_change'=>$input['email'],
                    //     'email_to_name'=>$memberRow->mem_fname,
                    //     'email_from'=>'noreply@liveloftus.com',
                    //     'email_from_name'=>$this->data['site_settings']->site_name,
                    //     'subject'=>'Email Update Request ',
                    //     'link'=>config('app.react_url')."/verification/".$userToken,
                    //     // 'code'=>$data['otp'],
                    // );
                    // $email=send_email($email_data,'emailChange');
                    $email_data=array(
                            'email_to'=>$input['email'],
                            'email_to_name'=>$memberRow->mem_fname,
                            'email_from'=>'noreply@liveloftus.com',
                            'email_from_name'=>$this->data['site_settings']->site_name,
                            'subject'=>'Email Verification',
                            'link'=>config('app.react_url')."/verification/".$userToken,
                            // 'code'=>$data['otp'],
                        );
                        $email=send_email($email_data,'account');

                    if($email){
                        $res['msg']="A verification link has been sent to your new email address.";
                        $res['status']=1;
                    }
                    else{
                        $res['msg']="Email could not be sent!";
                    }
                }
            } else {
                $res['member']=null;
            }

            exit(json_encode($res));
        }
    }
    public function update_password(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if(!empty($member)){
            $member=Member_model::where(['id' => $member->id])->get()->first();
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
                $memberRow=Member_model::where(['mem_password'=>md5($input['old_password'])])->get()->first();
                if(!empty($memberRow)){
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
            $res['member']=null;
        }

        exit(json_encode($res));
    }
    public function update_profile(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if(!empty($member)){
            $member=Member_model::where(['id' => $member->id])->get()->first();
            $input = $request->all();
            // $res['input']=date('d-m-Y',strtotime(json_decode($input['dob'])));
             // exit(json_encode($res));
            $country=json_decode($input['country']);
            $state=json_decode($input['state']);
            $member->mem_fname=$input['fname'];
            $member->mem_mname=!empty($input['mname']) ? $input['mname'] : '';
            $member->mem_lname=$input['lname'];
            $member->mem_dob=!empty($input['dob']) ? date('d-m-Y',strtotime(json_decode($input['dob']))) : '';
            $member->mem_bio=$input['bio'];
            $member->mem_address1=!empty($input['address1']) ? $input['address1'] : '';
            $member->mem_address2=!empty($input['address2']) ? $input['address2'] : '';
            $member->mem_city=!empty($input['city']) ? $input['city'] : '';
            $member->mem_country=$country->value;
            $member->mem_state=$state->value;
            $member->mem_zip=$input['zip_code'];
            $member->update();
            $res['msg']="Profile updated successfully!";
            $res['status']=1;
            // $res['dob']= date('d-m-Y',strtotime(json_decode($input['dob'])));
        }
        else{
            $res['member']=null;
        }

        exit(json_encode($res));
    }
    public function is_user_verified(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        if(!empty($member)){
            $res['member']=$member;
            $res['status']=1;
        }
        else{
            $res['member']=null;
        }

        exit(json_encode($res));
    }


}

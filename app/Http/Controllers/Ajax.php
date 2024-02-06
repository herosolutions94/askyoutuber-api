<?php

namespace App\Http\Controllers;

use App\Models\Properties_model;
use Stripe\StripeClient;
use App\Models\Member_model;
use Illuminate\Http\Request;
use App\Models\Contact_model;
use App\Models\Newsletter_model;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Validator;

class Ajax extends Controller
{



    public function create_stripe_intent(Request $request){
        $res=array();
        $res['status']=0;
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);
        $input = $request->all();
        if($input){
            $stripe = new StripeClient(
                $this->data['site_settings']->site_stripe_testing_secret_key
            );
            try{
                $amount = $input['amount'];
                if(!empty($input['expires_in'])){
                    // $expires_in=$input['expires_in'];
                    // $total=floatval($amount) * intval($expires_in);
                    $total=floatval($amount);
                }
                else{
                    $total=floatval($amount);
                }



                $cents = intval($total * 100);
                if(!empty($member->customer_id)){
                    $customer_id=$member->customer_id;
                }
                else{
                    $customer = $stripe->customers->create([
                        'email' =>$member->mem_email,
                        'name' =>$member->mem_fname." ".$member->mem_lname,
                        // 'address' => $stripe_adddress,
                    ]);
                    $customer_id=$customer->id;
                }

                $intent= $stripe->paymentIntents->create([
                    'amount' => $cents,
                    'currency' => 'usd',
                    'customer'=>$customer_id,
                    // 'payment_method' => $vals['payment_method'],
                    'setup_future_usage' => 'off_session',
                ]);
                $setupintent=$stripe->setupIntents->create([
                    'customer' => $customer_id,
                ]);
                $arr=array(
                        'paymentIntentId'=>$intent->id,
                        'setup_client_secret'=>$setupintent->client_secret,
                        'setup_intent_id'=>$setupintent->id,
                        'client_secret'=>$intent->client_secret,
                        'customer'=>$customer_id,
                        'status'=>1
                );
                $res['arr']=$arr;
                $res['status']=1;
                    // pr($arr);

            }
            catch(Exception $e) {
                $arr['msg']="Error >> ".$e->getMessage();
                $arr['status']=0;
            }
        }
        exit(json_encode($res));
    }
    public function get_data(){
        $details_url='https://nominatim.openstreetmap.org/search.php?q=virginia&polygon_geojson=1&format=json';
        $places=curl_get_request($details_url,'',true);
        // pr($places);
        if(is_array($places)){
            $location_set_var='';
            foreach($places as $p_key=>$place){
                    if(!empty($place->place_id) && !empty($place->geojson)){
                        if(!empty($place->geojson->coordinates)){
                            foreach($place->geojson->coordinates as $coordinates){

                                    if(!empty($coordinates) && is_array($coordinates)){
                                        $first_coord='';

                                        $all_coordinates='';
                                        foreach($coordinates as $key=>$coordinate){
                                            if(is_array($coordinate)){
                                                $all_coordinates.=($coordinate[1])." ".($coordinate[0]).",";
                                            }
                                            else{
                                                 $all_coordinates.=($coordinates[1])." ".($coordinates[0]).",";
                                            }

                                        }
                                        $location_set_var.="((".rtrim($all_coordinates,',').")),";
                                    }
                            }
                        }
                    }
            }
            // pr(rtrim($location_set_var,","));
            // $set_variable="ST_GEOMFROMTEXT(".rtrim($location_set_var,',').");";
            // $s_query = "SET @paris := ?";
            // DB::statement($s_query, [$set_variable]);
            // $results = DB::select('SELECT id,formatted_address FROM tbl_properties WHERE ST_CONTAINS(@paris, POINT(latitude,longitude))');
            // pr(rtrim($location_set_var,','));
            // $query="SET @paris = ST_GEOMFROMTEXT(".rtrim($location_set_var,',').");";
            // $query.='SELECT id,formatted_address FROM tbl_properties WHERE ST_CONTAINS(@paris, POINT(latitude,longitude))';
            // $query.=' SELECT id,formatted_address FROM tbl_properties';
            // $query .= DB::table('properties')
            // ->select('id', 'formatted_address')
            // ->where('ST_CONTAINS(@paris, POINT(latitude,longitude))')
            // ->get();
            // $results=DB::select($query);
            // $query = "SELECT id,formatted_address FROM tbl_properties WHERE ST_CONTAINS(@paris, CONCAT('POINT(', longitude, ' ', latitude, ')')";
            // pr(DB::raw($query));
            // $query = "SELECT id,formatted_address FROM `tbl_properties` WHERE ST_CONTAINS(@paris, CONCAT('POINT(', longitude, ' ', latitude, ')'))";
            // pr($query);
            // $results = DB::select('SELECT id,formatted_address FROM tbl_properties WHERE ST_CONTAINS(@paris, POINT(latitude,longitude))');
            // $query = "SELECT id,formatted_address FROM tbl_properties WHERE ST_Contains(ST_GeomFromText('POLYGON((".rtrim($location_set_var,",")."))'), POINT(latitude, longitude))";
            // $query = "SELECT id,formatted_address FROM tbl_properties WHERE ST_Contains(
            //     ST_GeomFromText(
            //         'MULTIPOLYGON(
            //             ".rtrim($location_set_var,",")."
            //         )'
            //     ), POINT(latitude, longitude))";

            // $results = DB::select(DB::raw($query));
            $multiPolygon="MULTIPOLYGON(
                ".rtrim($location_set_var,",")."
            )";
            $results = Properties_model::containsMultiPolygon($multiPolygon)->get();
            foreach($results as $res){
                pr($res->property_member_row);
            }
        }
    }
    public function get_states($country_id){
        $output='';
        $country_id=intval($country_id);
        if($country_id > 0 && $states=get_country_states($country_id)){
            foreach($states as $state){
				$output .='
						<option value="'.$state->id.'">'.ucfirst($state->name).'</option>
				';
			}
        }
        else{
			$output .='<option value="">No state(s) found!</option>';
		}
        exit(json_encode($output));
    }


    public function save_image(Request $request){
        $input = $request->all();
        $member=$this->authenticate_verify_token($input['token']);
        $res=array();
        $res['status']=0;
        $res['input']=$input;
        
        if(!empty($member)){
            
            if ($request->hasFile('image')) {

                $request_data = [
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    $image=$request->file('image')->store('public/members/');
                    if(!empty(basename($image))){
                        $member_row=Member_model::find($member->id);
                        $member_row->mem_image=basename($image);
                        $member_row->update();
                        $res['status']=1;
                        $res['msg']="Updated successfully!";
                        $res['mem_image']=get_site_image_src('members', !empty(basename($image)) ? basename($image) : '');
                    }
                    else{
                        $res['msg']="Something went wrong while uploading image. Please try again!";
                    }
                }


            }
            else{
                $res['image']="Only images are allowed to upload!";
            }
        }
        else{
            $res['status']=0;
            $res['msg']='Something went wrong!';
        }
        exit(json_encode($res));
    }
    public function upload_image(Request $request){
        $res=array();
        $res['status']=0;
            $input = $request->all();
            if ($request->hasFile('image')) {
                $type=$input['type'];
                $res['type']='public/'.$type.'/';
                $request_data = [
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    $image=$request->file('image')->store('public/'.$type.'/');
                    $res['image']=$image;
                    if(!empty(basename($image))){
                        $res['status']=1;
                        $res['image_name']=basename($image);
                        // $res['image_path']=storage_path('app/public/'.basename($image));
                    }
                    else{
                        $res['msg']="Something went wrong while uploading image. Please try again!";
                    }
                }


            }
            else{
                $res['image']="Only images are allowed to upload!";
            }

        exit(json_encode($res));
    }
    public function upload_file(Request $request){
        $res=array();
        $res['status']=0;
            $input = $request->all();
            if ($request->hasFile('file')) {

                $request_data = [
                    'file' => 'max:40000'
                ];
                $validator = Validator::make($input, $request_data);
                // json is null
                if ($validator->fails()) {
                    $res['status']=0;
                    $res['msg']='Error >>'.$validator->errors()->first();
                }
                else{
                    $image=$request->file('file')->store('public/attachments/');
                    $res['file_name']=$_FILES['file']['name'];
                    $res['file']=$image;
                    if(!empty(basename($image))){
                        $res['status']=1;
                        $res['file_name']=basename($image);
                    }
                    else{
                        $res['msg']="Something went wrong while uploading file. Please try again!";
                    }
                }


            }
            else{
                $res['msg']="Only images are allowed to upload!";
            }

        exit(json_encode($res));
    }
    public function save_newsletter(Request $request){
        $res=array();
        $res['status']=0;
        $input = $request->all();
        if($input){
            $request_data = [
                'email' => 'required|email|unique:newsletter,email',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']='Error >>'.$validator->errors()->first();
            }
            else{
                $data=array(
                    'email'=>$input['email'],
                    'status'=>0
                );
                Newsletter_model::create($data);
                $res['status']=1;
                $res['msg']='Subscribed successfully!';
            }

        }
        exit(json_encode($res));
    }
    public function contact_us(Request $request){
        $res=array();
        $res['status']=0;
        $input = $request->all();
        if($input){
            $request_data = [
                'email' => 'required|email',
                'name' => 'required',
                'phone' => 'required',
                'comments' => 'required',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']='Error >>'.$validator->errors();
            }
            else{
                $data=array(
                    'name'=>$input['name'],
                    'email'=>$input['email'],
                    'phone'=>$input['phone'],
                    'subject'=>'',
                    'message'=>$input['comments'],
                    'status'=>0
                );
                Contact_model::create($data);
                $res['status']=1;
                $res['msg']='Message sent successfully!';
            }

        }
        exit(json_encode($res));
    }
    public function reset_password(Request $request,$token){
        $res=array();
        $res['status']=0;
        $member=$this->authenticate_verify_token($token);
        if($member){
            if($member=='expired'){
                $res['msg']="Link timeout. Send request again to reset your password.";
            }
            else{
                $input = $request->all();
                if($input){
                    $request_data = [
                        'password' => 'required',
                        'confirm_password' => 'required|same:password',
                    ];
                    $validator = Validator::make($input, $request_data);
                    // json is null
                    if ($validator->fails()) {
                        $res['status']=0;
                        $res['msg']=convertArrayMessageToString($validator->errors()->all());
                    }
                    else{
                        $member->mem_password=md5($input['password']);
                        $member->update();
                        $res['msg']="Password reset successfully!";
                        $res['status']=1;
                    }
                }
                else{
                    $res['msg']='Nothing to reset';
                }
            }

        }
        else{
            $res['msg']='This user does not exist';
            $res['status']=0;
        }

        exit(json_encode($res));
    }
    public function forget_password(Request $request){
        $res=array();
        $res['status']=0;
        $input = $request->all();
        if($input){
            $request_data = [
                'email' => 'required|email',
            ];
            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']=convertArrayMessageToString($validator->errors()->all());
            }
            else{
                $member=Member_model::where(['mem_email' => $input['email']])->get()->first();
                if(!empty($member)){
                    if($member->mem_status==1){

                        $mem_id=$member->id;
                        $token=$mem_id."-".$member->mem_email."-".$member->mem_type."-".rand(99,999);
                        $userToken=encrypt_string($token);
                        $token_array=array(
                            'mem_type'=>$member->mem_type,
                            'token'=>$userToken,
                            'mem_id'=>$mem_id,
                            'expiry_date'=>date("Y-m-d", strtotime("6 months")),
                        );
                        DB::table('tokens')->insert($token_array);
                        $verify_link=config('app.react_url')."/reset-password/".$userToken;
                        $res['verify_link']=$verify_link;
                        $email_data=array(
                            'email_to'=>$member->mem_email,
                            'email_to_name'=>$member->mem_fname,
                            'email_from'=>'noreply@liveloftus.com',
                            'email_from_name'=>$this->data['site_settings']->site_name,
                            'subject'=>'Password Reset Request',
                            'link'=>$verify_link,
                            // 'code'=>$data['otp'],
                        );
                        $email=send_email($email_data,'forget');
                        $res['status']=1;
                        $res['msg']='Email has been sent to reset your password.';
                    }
                    else{
                        $res['msg']='Your account is not active right now. Ask website admit to activate your account!';
                    }
                }
                else{
                    $res['msg']='Email does not exist.';
                }
            }
        }
        exit(json_encode($res));
    }
    public function signup(Request $request){
        $res=array();
        $res['status']=0;
        $input = $request->all();

        if($input){
            if($input['type']=='google'){
                $request_data = [
                    'googleId' => 'required',
                    'email' => 'required|email|unique:members,mem_email',
                    'fname' => 'required',
                    'lname' => 'required',
                    'phone' => 'required|unique:members,mem_phone',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password',
                ];
            }
            else{
                $request_data = [
                    'email' => 'required|email|unique:members,mem_email',
                    'fname' => 'required',
                    'lname' => 'required',
                    'password' => 'required',
                    'confirm_password' => 'required|same:password',
                ];
            }

            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']=convertArrayMessageToString($validator->errors()->all());
            }
            else{
                if($input['type']=='google' && !empty($input['googleId'])){

                    $member_count=Member_model::where(['mem_email'=>$input['email'],'googleId'=>$input['googleId']])->get()->count();
                    if(intval($member_count) > 0){
                        $res['google_status']=1;
                        $res['status']=0;
                        $res['msg']='Authentication Error >> Google ID does not exist for your email. Please use email and password to login!';
                        exit(json_encode($res));
                    }
                    else{
                        $data=array(
                            'mem_type'=>'member',
                            'mem_image'=>!empty($input['user_image']) ? write_image($input['user_image'],'public/members/') : "",
                            'googleId'=>!empty($input['googleId']) ? $input['googleId'] : "",
                            'mem_fname'=>$input['fname'],
                            'mem_lname'=>$input['lname'],
                            'mem_email'=>$input['email'],
                            'mem_phone'=>$input['phone'],
                            'mem_password'=>md5($input['password']),
                            'otp'=>random_int(100000, 999999),
                            'otp_phone'=>random_int(100000, 999999),
                            'otp_expire'=>date('Y-m-d H:i:s', strtotime('+3 minute')),
                            'mem_status'=>1,
                            'mem_email_verified'=>1
                        );
                    }

                }
                else{
                    $data=array(
                        'mem_type'=>'member',
                        'mem_fname'=>$input['fname'],
                        'mem_lname'=>$input['lname'],
                        'mem_email'=>$input['email'],
                        'mem_password'=>md5($input['password']),
                        'otp'=>random_int(100000, 999999),
                        'otp_expire'=>date('Y-m-d H:i:s', strtotime('+3 minute')),
                        'mem_status'=>1
                    );
                }

                // pr($data);

                $mem_data=Member_model::create($data);
                $mem_id=$mem_data->id;
                if($mem_id > 0){
                    $userToken=$this->create_mem_token($mem_id);
                    $res['mem_type']=$data['mem_type'];
                    $res['authToken']=$userToken;
                    $res['status']=1;
                    $res['msg']='You are register successfully. And We’ve sent a verify email to your email. If you don’t see the email, check your spam folder. And we are unable to send otp to your phone number because of some technical problem!';
                }
                else{
                    $res['status']=0;
                    $res['msg']='Technical problem!';
                }

            }

        }
        exit(json_encode($res));
    }
    public function login(Request $request){
        $res=array();
        $res['status']=0;
        $res['google_status']=0;
        $input = $request->all();

        if($input){
            if($input['type']=='google'){
                $request_data = [
                    'googleId' => 'required',
                ];
            }
            else{
                $request_data = [
                    'email' => 'required|email',
                    'password' => 'required',
                ];
            }

            $validator = Validator::make($input, $request_data);
            // json is null
            if ($validator->fails()) {
                $res['status']=0;
                $res['msg']='Error >>'.$validator->errors();
            }
            else{
                if($input['type']=='google'){
                    $profileData=json_decode($input['profileObj']);
                    $member=Member_model::where(['mem_email' => $profileData->email])->get()->first();
                    if(!empty($member)){
                        if(empty($member->googleId)){
                            $res['google_status']=1;
                            $res['status']=0;
                            $res['msg']='Authentication Error >> Google ID does not exist for your email. Please use wmail and password to login!';
                            exit(json_encode($res));
                        }
                        else{
                            $mem_id=$member->id;
                            $userToken=$this->create_mem_token($mem_id);
                            $res['authToken']=$userToken;
                            if($member->mem_verified==1){
                                $res['status']=1;
                                $res['google_status']=1;
                                $res['msg']='Logged In successfully!';
                            }
                            else{
                                if(empty($member->mem_phone_verified) || $member->mem_phone_verified==0){
                                    $res['phone_verified']=1;
                                }
                                $res['status']=1;
                                $res['google_status']=1;
                                $res['not_verified']=1;
                                $res['msg']='Logged In successfully!';
                            }
                        }
                    }
                    else{
                        $res['status']=0;
                        $res['google_status']=1;
                        $res['msg']='This Google account is not registered in our system. Please proceed to the sign up page to register this account, or login with a different account.';
                        exit(json_encode($res));
                    }
                }
                else{
                    $member=Member_model::where(['mem_email' => $input['email'],'mem_password'=>md5($input['password'])])->get()->first();
                    if(!empty($member)){
                        if($member->mem_status==1){

                            $mem_id=$member->id;
                            $userToken=$this->create_mem_token($mem_id);
                            $res['authToken']=$userToken;
                            $res['mem_type']=$member->mem_type;
                            if($member->mem_verified==1){
                                $res['status']=1;
                                $res['msg']='Logged In successfully!';
                            }
                            else{
                                $res['status']=1;
                                $res['not_verified']=1;
                                $res['msg']='Logged In successfully!';
                            }
                        }
                        else{
                            $res['msg']='Your account is not active right now. Ask website admit to activate your account!';
                        }
                    }
                    else{
                        $res['msg']='Email or password is not correct. Please try again!';
                    }
                }

            }
        }
        exit(json_encode($res));
    }
    public function verify_otp(Request $request){
        $res=array();
        $res['status']=0;
        $res['email_verify']=0;
        $input = $request->all();
        $header = $request->header('Authorization');
        $member=$this->authenticate_verify_token($header);

        // exit(json_encode($res));
        if(!empty($member)){
            if($input){
                $phone=$member->phone_change;
                $old_phone=$member->mem_phone;
                    if(strtotime(date('Y-m-d H:i:s')) > strtotime(date('Y-m-d H:i:s',strtotime($member->otp_expire)))){
                        $res['msg']="Your OTP has expired. Please resend a new OTP to verify your phone number. ";
                        $res['status']=0;
                        $res['expired']=1;
                        exit(json_encode($res));
                    }
                    if(!empty($phone)){
                        $otp_verify=verifyOtp($input[0],$phone,$member->otp_phone);
                        $member->phone_change='';
                        $member->mem_phone=$phone;
                    }
                    else{
                        $otp_verify=verifyOtp($input[0],$member->mem_phone,$member->otp_phone);
                    }


                    if($otp_verify==true || $otp_verify==1){
                        $member_row=Member_model::find($member->id);
                        $member_row->otp_phone='';


                        if($member_row->mem_email_verified===1){
                            $member_row->mem_verified=1;
                            $member_row->otp_phone='';
                            $member_row->otp='';
                        }
                        else{
                            $res['email_verify']=1;
                        }
                        $member_row->mem_phone_verified=1;
                        $member_row->mem_status=1;
                        // $res['phone']=$phone;
                        // exit(json_encode($res));
                        $member_row->update();
                        $mem_id=$member->id;
                        $token=$mem_id."-".$member->mem_email."-".$member->mem_type."-".rand(99,999);
                        $userToken=encrypt_string($token);
                        $token_array=array(
                            'mem_type'=>$member->mem_type,
                            'token'=>$userToken,
                            'mem_id'=>$mem_id,
                            'expiry_date'=>date("Y-m-d", strtotime("6 months")),
                        );
                        DB::table('tokens')->insert($token_array);
                        $res['mem_type']=$member->mem_type;
                        $res['authToken']=$userToken;
                        $res['status']=1;
                        $res['msg']='Your account has been verified successfully!';
                    }
                    else{
                        $res['otp_verify']=$otp_verify;
                        $res['msg']='The OTP you entered is incorrect. Please enter the correct OTP or resend a new OTP. ';
                    }
                    exit(json_encode($res));




                // }
                // else{
                //     $res['status']=0;
                //     $res['msg']='OTP is not correct!';
                // }


            }
        }
        else{
            $res['status']=0;
            $res['msg']='Something went wrong!';
        }

        exit(json_encode($res));
    }

}

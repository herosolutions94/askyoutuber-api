<?php

use App\Models\Admin;
use App\Models\Blog_model;
use App\Models\Listing_model;
use App\Models\Listing_prices_model;
use App\Models\Member_model;
use App\Models\Sitecontent;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
// use Image;
use Intervention\Image\Facades\Image;

function resize_crop_image($path,$image,$type='thumb_',$width=500,$height=500){
    // try {
        ini_set('memory_limit','1200M');
        if (!empty($image) && @file_exists(".".Storage::url($path.'/'.$image))) {
            // pr($image);
            $imagePath = public_path('storage/'.$path.'/'.$image);
            $thumbnailpath = public_path('storage/'.$path.'/thumbs/'.$type.$image);
            $img=Image::make($imagePath)->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumbnailpath)->destroy();
            return $img;
        }

    // } catch (\Exception $e) {

    //     $msg=$e->getMessage();
    //     return false;
    // }


}
function get_branch_baths($branch_full_bathrooms){
    $min=null;
    $max=null;
    if(!empty($branch_full_bathrooms)){
        $min=$branch_full_bathrooms[0]->all_bathrooms;
        $max=$branch_full_bathrooms[0]->all_bathrooms;
        foreach($branch_full_bathrooms as $key=>$f_bath){


            if($f_bath->all_bathrooms < $min){
                $min=$f_bath->all_bathrooms;
            }
            if($f_bath->all_bathrooms > $max){
                $max=$f_bath->all_bathrooms;
            }
        }
    }
    return(array("min"=>$min,'max'=>$max));
}
function write_image($url,$path){
    $contents = file_get_contents($url);
    // pr($contents);
    $file_name=md5(rand(100, 1000)) . '_' .time() . '_' . rand(1111, 9999). '.jpg';

    Storage::put($path.$file_name, $contents);
    return $file_name;
}
function format_address($address){
    if(!empty($address)){
        $address_arr=explode( ",", $address);

        if(count($address_arr) == 5){
            return nl2br($address_arr[0].", ".$address_arr[1]."\n".$address_arr[2].", ".$address_arr[3].", ".$address_arr[4]);
        }
        else if(count($address_arr) == 4){
            return nl2br($address_arr[0]."\n".$address_arr[1].", ".$address_arr[2].", ".$address_arr[3]);
        }
        else if(count($address_arr) == 3){
            return nl2br($address_arr[0]."\n".$address_arr[1].", ".$address_arr[2]);
        }
        else if(count($address_arr) == 2){
            if(str_contains($address_arr[1], 'USA')){
                return nl2br($address_arr[0]);
            }
            else{
                return nl2br($address_arr[0]."\n".$address_arr[1]);
            }

        }
        else{
            return $address;
        }

    }
    else{
        return '';
    }
    // return $address;

}
function format_address_single($address){
    // if(!empty($address)){
    //     $address_arr=explode( ",", $address);
    //     // $address_arr=array_reverse($address_arr);
    //      if(count($address_arr) == 5){
    //         return nl2br($address_arr[0].", ".$address_arr[1].",".$address_arr[2].", ".$address_arr[3]);
    //     }
    //     else if(count($address_arr) == 4){
    //         return $address_arr[0].", ".$address_arr[1].", ".$address_arr[2];
    //     }
    //     else if(count($address_arr) == 3){
    //         return $address_arr[0].", ".$address_arr[1];
    //     }
    //     else if(count($address_arr) == 2){
    //         return nl2br($address_arr[0]);
    //     }
    //     else{
    //         return $address;
    //     }

    // }
    // else{
    //     return '';
    // }
    return $address;

}
function format_address_one_line($address){
    if(!empty($address)){
        $address_arr=explode( ",", $address);
        // $address_arr=array_reverse($address_arr);
         if(count($address_arr) == 5){
            return nl2br($address_arr[0].", ".$address_arr[1].",".$address_arr[2].", ".$address_arr[3]);
        }
        else if(count($address_arr) == 4){
            return $address_arr[0].", ".$address_arr[1].", ".$address_arr[2];
        }
        else if(count($address_arr) == 3){
            return $address_arr[0].", ".$address_arr[1];
        }
        else if(count($address_arr) == 2){
            return nl2br($address_arr[0]);
        }
        else{
            return $address;
        }

    }
    else{
        return '';
    }

}

function isCheckedFeature($features,$type='den'){

    if(!empty($features) && count($features) > 0){
        foreach($features as $feature){

            $feature_id=intval(json_decode($feature));
            // pr(get_amentiy_name($feature_id));
            if(strtolower(get_amentiy_name($feature_id))==$type){
                return true;
            }
        }
    }
    else{
        return false;
    }
}
function encrypt_string($string)
{
    return Crypt::encryptString($string);
}
function decrypt_string($string)
{
    return Crypt::decryptString($string);
}
function doEncode($string, $key = 'preciousprotection')
{
    $hash='';
    $string = base64_encode($string);
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j=0;
    for ($i = 0; $i < $strLen; $i++) {

        $ordStr = ord(substr($string, $i, 1));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey), 16, 36));
    }
    return ($hash);
}
function doDecode($string, $key = 'preciousprotection')
{
    $hash='';
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j=0;
    for ($i = 0; $i < $strLen; $i += 2) {
        $ordStr = hexdec(base_convert(strrev(substr($string, $i, 2)), 36, 16));
        if ($j == $keyLen) {
            $j = 0;
        }
        $ordKey = ord(substr($key, $j, 1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    $hash = base64_decode($hash);
    return ($hash);
}
function setInvoiceNo($invoice_id)
{

    $output = NULL;

    for ($i = 0; $i < 6 - strlen($invoice_id); $i++) {

        $output .= '0';
    }

    return "offer_" . $output . $invoice_id;
}
function writ_post_data($file_name,$post) {
    Storage::put('public/logs/'.$file_name.date('Y-m-d H:i:s').'.txt', json_encode($post));
}
function create_notification($data,$type='notification'){
    $data['updated_at']=date('Y-m-d h:i:s');
    $data['created_at']=date('Y-m-d h:i:s');
    $data['type']=$type;
    // pr($data);
    DB::table('notifications')->insert($data);
}
function updateRecord($table,$field,$value,$arr){
    // pr($arr);
    $id=DB::table($table)->where($field,$value)->update($arr);
    return $id;
}
function save_data($data,$table){
    // $data['updated_at']=date('Y-m-d h:i:s');
    // $data['created_at']=date('Y-m-d h:i:s');
    // pr($data);
    $id=DB::table($table)->insert($data);
    return $id;
}
function get_notifications($mem_id){
    $res=[];
    $res['count']=0;
    $res['content']=[];
    $notification= DB::table('notifications')->orderBy('id', 'desc')->where('mem_id', $mem_id)->get();
    if(!empty($notification)){
        $res['count']=$notification->count();
        $res['unread']=DB::table('notifications')->where(['mem_id'=> $mem_id,'status'=>0])->get()->count();
        foreach($notification as $notify){
            $member_row=Member_model::where(['id' => $notify->mem_id])->get()->first();
            $obj=(object)[];
            $obj->id=$notify->id;
            $obj->name=$member_row->mem_fname." ".$member_row->mem_lname;
            $obj->thumb=get_site_image_src('members', !empty($member_row) ? $member_row->mem_image : '');
            $obj->text=$notify->text;
            $obj->time=time_ago($notify->created_at);
            $res['content'][]=$obj;
        }
    }
    return $res;
}
function get_site_image_src($path, $image, $type = '', $user_image = false)
{
    $filepath = Storage::url($path.'/'.$type.$image);
    if (!empty($image) && @file_exists(".".Storage::url($path.'/'.$type.$image))) {
    // if (!empty($image) && @getimagesize($filepath)) {
        return url($filepath);
    }
    return empty($user_image) ? asset('images/no-image.svg') : asset('images/no-user.svg');
}
function get_site_video($path, $video)
{
    $filepath = Storage::url($path.'/'.$video);
    if (!empty($video) && @file_exists(".".Storage::url($path.'/'.$video))) {
        return $filepath;
    }
    return asset('videos/404.mp4');
}
function removeImage($path)
{
    if(file_exists(".".Storage::url($path))){
        unlink(".".Storage::url($path));
    }
}
function pr($data){
    print_r($data);die;
}
function getSelectObject($value,$label=''){
    $object=(object)[];
    if(!empty($label)){
        $object->label=$label;
    }
    else{
        $object->label=$value;
    }

    $object->value=$value;
    return $object;
}

function upload_error($file){
    $error_types = array(
        1=>'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        'The uploaded file was only partially uploaded.',
        'No file was uploaded.',
        6=>'Missing a temporary folder.',
        'Failed to write file to disk.',
        'A PHP extension stopped the file upload.'
        );
        return $error_types[$file];
}
function getMultiText($section)
{
   return DB::table('multi_text')->where('section', $section)->get();
}
function saveMultiText($vals,$section){
    if(count($vals['title']) > 0){
        for ($i = 0; $i < count($vals['title']); $i++) {
            $arr['section'] = $section;
            $arr['title'] = ($vals['title'][$i] != '') ? $vals['title'][$i] : '';
            $arr['detail'] = ($vals['detail'][$i] != '') ? $vals['detail'][$i] : '';
            $arr['txt1'] = isset($vals['txt1'][$i]) != '' ? $vals['txt1'][$i] : '';
            $arr['txt2'] = isset($vals['txt2'][$i]) != '' ? $vals['txt2'][$i] : '';
            $arr['txt3'] = isset($vals['txt3'][$i]) != '' ? $vals['txt3'][$i] : '';
            $arr['txt4'] = isset($vals['txt4'][$i]) != '' ? $vals['txt4'][$i] : '';
            $arr['txt5'] = isset($vals['txt5'][$i]) != '' ? $vals['txt5'][$i] : '';
            $arr['order_no'] = ($vals['order_no'][$i] != '') ? $vals['order_no'][$i] : '';
            DB::table('multi_text')->insert($arr);
        }
    }
}
function saveData($vals,$table){
    $id=DB::table($table)->insert($vals);
    return $id;
}

function delete_record($table,$field,$value){
    DB::table($table)->where($field, $value)->delete();
}
function get_countries($where=array("id"=>231)){
    $options = "";
    $rows=DB::table('countries')->where($where)->get();
    return $rows;
}
function findPropertyAddress($where){
    pr($where);
    $options = "";
    $rows=DB::table('properties')->where($where)->get();
    return $rows->first();
}
function convertArrayToSelectArray($array){
    $rows=array();
    if(!empty($array)){
        foreach($array as $arr){
            $item=(object)[];
            $item->value=$arr->id;
            $item->label=$arr->name;
            $rows[]=$item;
        }
    }
    return $rows;
}
function get_country_name($id){
    if(intval($id) > 0 && $row= DB::table('countries')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->name;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_mem_name($id){
    if(intval($id) > 0 && $row= DB::table('members')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->mem_fname." ".$row->mem_lname;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_mem_row($id){
    if(intval($id) > 0 && $row= DB::table('members')->where('id', $id)->first()){
        return $row;
    }
    else{
        return false;;
    }
}

function get_amentiy_name($id){
    if(intval($id) > 0 && $row= DB::table('amenties')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->title;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_floor_plan_name($id){
    if(intval($id) > 0 && $row= DB::table('floor_plans')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->floor_plan;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_floor_plan($id){
    if(intval($id) > 0 && $row= DB::table('floor_plans')->where('id', $id)->first()){
       return $row;
    }
    else{
        return false;
    }
}
function format_amount($amount, $size = 2)
{
    $amount = floatval($amount);
    return $amount >= 0 ? "$".number_format($amount, $size) : "$ (".number_format(abs($amount), $size).')';
}
function format_number($amount, $size = 2)
{
    $amount = floatval($amount);
    return number_format(abs($amount), $size);
}
function get_branch_name($id){
    if(intval($id) > 0 && $row= DB::table('branches')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->name;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_branch_description($id){
    if(intval($id) > 0 && $row= DB::table('branches')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->description;
        }
        else{
            return '';
        }
    }
    else{
        return '';
    }
}
function get_property_name($id){
    if(intval($id) > 0 && $row= DB::table('properties')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->title;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_property($id){
   return $row= DB::table('properties')->where('id', $id)->first();
}
function get_property_member($id){
    if(intval($id) > 0 && $row= DB::table('properties')->where('id', $id)->first()){
        if(!empty($row)){
            return get_mem_name($row->mem_id);
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_mem_properties($mem_id){
    if(intval($mem_id) > 0 && $row= DB::table('properties')->where('mem_id', $mem_id)->get()){
        if(!empty($row)){
            return $row->count();
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function AddMonths($months,$date=''){
    if($date!=''){
        $total_months=date('Y-m-d', strtotime("+".$months." months", strtotime(date('Y-m-d',strtotime($date)))));
    }
    else{
        $total_months=date('Y-m-d', strtotime("+".$months." months", strtotime(date('Y-m-d'))));
    }

    return $total_months;
}
function add_days($months,$date){
    if(!empty($date)){
        $total_days=intval($months) * 30;
        $final_date=date('Y-m-d', strtotime("+".$total_days." days", strtotime(date('Y-m-d',strtotime($date)))));
    }
    else{
        $final_date=date('Y-m-d', strtotime("+".$total_days." days", strtotime(date('Y-m-d'))));
    }
    return $final_date;
}
function convertArrayToStringMessage($errors){
    $message='';
    if(is_array($errors)){
        foreach($errors as $err){
            $message.=$err->message;
        }
    }
    else{
        $message=$errors;
    }
    return $message;
}
function getPackageID($package){
    if ($package == 'N') {
        return 0;
    }
    else if ($package == 'CC') {
        return 5002;
    }
    else if ($package == 'CCE') {
        return 5003;
    }
    else if ($package == 'CCI') {
        return 5007;
    }
    else if ($package == 'CCEI') {
        return 5004;
    }
    else {
        return 0;
    }
}
function get_mem_packages_names($mem_id){
    $mem_package=DB::table('mem_packages')->where('mem_id', $mem_id)->where('expiry_date','>',date("Y-m-d"))->orderBy('expiry_date','DESC')->get();
    $packages=array();
    foreach($mem_package as $pkg){
        $packages[]=$pkg->package;
    }
    return $packages;
}
function getOfferPackage($package,$mem_id){
    $packages=get_mem_packages_names($mem_id);
    if(!empty($packages)){
        if ($package == 'CC') {
            if(in_array("CC",$packages)==true || in_array("CCEI",$packages)==true){
                return false;
            }
            else{
                return true;
            }
        }
        else if ($package == 'CCE') {
            if(in_array("CCEI",$packages)==true){
                return false;
            }
            else{
                return true;
            }
        }
        else if ($package == 'CCI') {
            if(in_array("CCEI",$packages)==true){
                return false;
            }
            else{
                return true;
            }
        }
        else if ($package == 'CCEI') {
            // if(in_array("CCE",$packages)==true && in_array("CCI",$packages)==true){
            //     return false;
            // }
            if(in_array("CCEI",$packages)==true){
                return false;
            }
            else{
                return true;
            }
        }
        else {
            return true;
        }
    }


}
function getGoogleMapAddress($address){
    $key="AIzaSyAyno9DSD3KxzzvS32ZPlvpSQHujSDIvSo";
   $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address['line1']." ".$address['line2'].", ".$address['city']." ".$address['state']." ".$address['zip_code']."&key=AIzaSyAyno9DSD3KxzzvS32ZPlvpSQHujSDIvSo";
   $newUrl = str_replace(' ', '%20', $details_url);
   // pr($newUrl);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $newUrl);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $result = curl_exec($ch);
    if ($result === false)
    {
        // throw new Exception('Curl error: ' . curl_error($crl));
       return array(
            'error'=>curl_error($ch),
            'status'=>0
       );
    }
    // Close cURL resource
    curl_close($ch);
    $res=json_decode($result);
    if($res->status=='OK' || $res->status=='ok'){
        return array(
            'address'=>format_address_one_line($res->results[0]->formatted_address),
            'latitude'=>$res->results[0]->geometry->location->lat,
            'longitude'=>$res->results[0]->geometry->location->lng,
            'place_id'=>$res->results[0]->place_id,
            'status'=>1
        );
    }
    return array(
            'error'=>$res->status,
            'status'=>0
    );

}

function getGoogleMapAddressAPI($address){
    $key="AIzaSyAyno9DSD3KxzzvS32ZPlvpSQHujSDIvSo";
   $details_url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address['line1']." ".$address['line2'].", ".$address['city']." ".$address['state']." ".$address['zip_code']."&key=AIzaSyAyno9DSD3KxzzvS32ZPlvpSQHujSDIvSo";
   $newUrl = str_replace(' ', '%20', $details_url);
   // pr($newUrl);
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $newUrl);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $result = curl_exec($ch);
    if ($result === false)
    {
        // throw new Exception('Curl error: ' . curl_error($crl));
       return array(
            'error'=>curl_error($ch),
            'status'=>0
       );
    }
    // Close cURL resource
    curl_close($ch);
    $res=json_decode($result);
    if($res->status=='OK' || $res->status=='ok'){
        pr($res);
        $components = $res->results[0]->address_components;
        // pr($components);
        $street_number = array_values(filter($components, "street_number"))[0]->long_name;
        $route = array_values(filter($components, "route"))[0]->long_name;
        $neighborhood = array_values(filter($components, "neighborhood"))[0]->long_name;
        $locality = array_values(filter($components, "locality"))[0]->long_name;
        $zipcode = array_values(filter($components, "postal_code"))[0]->long_name;
        $citystate = array_values(filter($components, "administrative_area_level_1"))[0]->long_name;
        pr($street_number." ".$route." ".$neighborhood.", ".$locality.", ".$citystate.", ".$zipcode);
        return array(
            'address'=>$res->results[0]->formatted_address,
            'latitude'=>$res->results[0]->geometry->location->lat,
            'longitude'=>$res->results[0]->geometry->location->lng,
            'place_id'=>$res->results[0]->place_id,
            'status'=>1
        );
    }
    return array(
            'error'=>$res->status,
            'status'=>0
    );

}
function filter($components, $type)
{
    return array_filter($components, function($component) use ($type) {
        return array_filter($component->types, function($data) use ($type) {
            return $data == $type;
        });
    });
}
function curl_request($url,$payload,$token='',$put=false){
    $ch = curl_init($url);

    // Attach encoded JSON string to the POST fields
    if($put==true || $put==1){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    $headers = array(
        'Content-Type:application/json',
        "Authorization: ".$token."",
     );
    // Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Execute the POST request
    $result = curl_exec($ch);
    if ($result === false)
    {
        // throw new Exception('Curl error: ' . curl_error($crl));
       return 'Curl error: ' . curl_error($ch);
    }
    // Close cURL resource
    curl_close($ch);
    return json_decode($result);
}
function convertPhoneToNumber($phone){
    $phone=str_replace(array( '(', ')' ), '', $phone);
    $phone=str_replace(' ', '', $phone);
    $phone=str_replace('+', '', $phone);
    $phone=str_replace('-', '', $phone);
    $phone=substr($phone, 1);
    return $phone;
}
function truncate_number( $number, $precision = 2) {
    // // Zero causes issues, and no need to truncate
    // if ( 0 == (int)$number ) {
    //     return $number;
    // }
    // // Are we negative?
    // $negative = $number / abs($number);
    // // Cast the number to a positive to solve rounding
    // $number = abs($number);
    // // Calculate precision number for dividing / multiplying
    // $precision = pow(10, $precision);
    // // Run the math, re-applying the negative value to ensure returns correctly negative / positive
    // return floor( $number * $precision ) / $precision * $negative;
    return $number;
}
function curl_get_request($url,$token='',$openstreetmap=false){
    $ch = curl_init($url);

    // Attach encoded JSON string to the POST fields
    if(!empty($token)):
        $headers = array(
            'Content-Type:application/json',
            "Authorization: ".$token."",
         );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    endif;
    if($openstreetmap):
         $headers = array(
                "Content-Type: application/json",
                "header" => "User-Agent: Nominatim-Test"
           );
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    endif;
    // Set the content type to application/json


    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Execute the POST request
    $result = curl_exec($ch);
    if ($result === false)
    {
        // throw new Exception('Curl error: ' . curl_error($crl));
       return 'Curl error: ' . curl_error($ch);
    }
    // Close cURL resource
    curl_close($ch);
    return json_decode($result);
}
function createTransUnionToken(){
    // API URL
    $url = config('app.transunion_api').'Tokens';
    $data = [
        'clientId' => 'tM0h.VRIoSmPeZuZfn7PP3et1tTHPpU-',
        'apiKey' => '37riyL$HEQaHQDSOlENP!1xdIcM68df^',
    ];
    $payload = json_encode($data);

    $ch = curl_init($url);

    // Attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Set the content type to application/json
    if(!empty($token)){
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization'=>$token));
    }
    else{
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    }

    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the POST request
    $result = curl_exec($ch);
    if ($result === false)
    {
        // throw new Exception('Curl error: ' . curl_error($crl));
       return 'Curl error: ' . curl_error($ch);
    }
    // Close cURL resource
    curl_close($ch);
    return json_decode($result);
}

function getCompanyListingPrice($property){
    $listings=Listing_model::where(['property'=>$property,'mem_type'=>'company'])->get();
    if(count($listings) >  0){
        $max_price=0;
        $min_price=0;
        foreach($listings as $key=>$listing){
            $max=Listing_prices_model::where('listing_id', $listing->id)->max('price');
            $min=Listing_prices_model::where('listing_id', $listing->id)->min('price');
            if($key==0){
                $max_price=$max;
                $min_price=$min;
            }
            else{
                if($max > $max_price){
                    $max_price=$max;
                }
                if($min < $min_price){
                    $min_price=$min;
                }
            }

        }
        return ['max_price'=>$max_price,'min_price'=>$min_price];
    }
    return false;
}
function getDays($future_date){
    $now = time(); // or your date as well
    $your_date = strtotime($future_date);
    $datediff = $your_date - $now;

    return round($datediff / (60 * 60 * 24));
}
function getListingDays($future_date){
    $now = time(); // or your date as well
    $your_date = strtotime($future_date);
    $datediff = $now - $your_date;

    return round($datediff / (60 * 60 * 24));
}
function get_property_image($id){
    if(intval($id) > 0 && $row= DB::table('properties')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->imageThumbnail;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_landlord_reports($srrId){
    $reports=[];
    $token=createTransUnionToken();
    $reportNames= curl_get_request(config('app.transunion_api').'/Landlords/ScreeningRequestRenters/'.$srrId.'/Reports/Names',$token->token);

    if(empty($reportNames->errors)){

        if(is_array($reportNames)){
            foreach($reportNames as $key=>$name){
                $report=(object)[];
                $getReport= curl_get_request(config('app.transunion_api').'/Landlords/ScreeningRequestRenters/'.$srrId.'/Reports?requestedProduct='.$name,$token->token);

                if(!empty($getReport->reportResponseModelDetails)){
                    $report->pending=false;
                    $report->type=$getReport->reportResponseModelDetails[0]->providerName;
                    $report->report=$getReport->reportResponseModelDetails[0]->reportData;
                    $reports[]=$report;
                }
                else{
                    $report->pending=true;
                    $report->type=$name;
                    $report->report=$getReport->name." >> ".$getReport->message;
                    $reports[]=$report;
                }
            }
        }
    }
    return $reports;
}
function get_mem_packages($mem_id,$input_package){
    $res=[];
    $count= DB::table('mem_packages')->where('mem_id', $mem_id)->where('expiry_date', '>=' , date('Y-m-d'))->count();
    if(intval($count) > 0){
        $packages= DB::table('mem_packages')->where(['mem_id'=>$mem_id])->where('expiry_date', '>=' , date('Y-m-d'))->pluck('package')->toArray();
        if($input_package=='CC'){
            if(in_array($input_package, $packages)){
                $res['found']=1;
            }
            else{
                foreach($packages as $pkg){
                    if(getPackageID($pkg) > getPackageID($input_package)){
                        $res['found']=1;
                        return $res;
                    }
                    else{
                        $res['found']=0;
                    }
                }
            }
        }
        else if($input_package=='CCE'){
            if(in_array($input_package, $packages)){
                $res['found']=1;
            }
            else{
                if(in_array('CCEI', $packages)){
                    $res['found']=1;
                }
                else{
                    $res['found']=0;
                }
            }
        }
        else if($input_package=='CCI'){
            if(in_array($input_package, $packages)){
                $res['found']=1;
            }
            else{
                if(in_array('CCEI', $packages)){
                    $res['found']=1;
                }
                else{
                    $res['found']=0;
                }
            }
        }
        else if($input_package=='CCEI'){
            if(in_array($input_package, $packages)){
                $res['found']=1;
            }
            else{
                if(count($packages) >= 2){
                    if(!in_array('CCI', $packages)){
                        $res['found']=0;
                    }
                    else if(!in_array('CCE', $packages)){
                        $res['found']=0;
                    }
                    else{
                        $res['found']=1;
                    }
                }
                else{
                    $res['found']=0;
                }
            }
        }
        else{
            if(!in_array($input_package, $packages)){
                $res['found']=0;
            }
            else{
                $res['found']=1;
            }
        }
    }
    else{
        $res['found']=0;
    }
    return $res;
}
function send_email($data,$template){
    require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions

        try {
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            $mail->SMTPDebug = 0;
            $mail->ContentType = 'text/html; charset=utf-8';
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = 'email-smtp.us-east-1.amazonaws.com';
            //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
            // I tried PORT 25, 465 too
            $mail->Port = 587;
            //Set the encryption system to use - ssl (deprecated) or tls
            $mail->SMTPSecure = 'tls';
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );
            //Username to use for SMTP authentication - use full email address for gmail
            $mail->Username = "AKIAT6A63I5A4WLXGITD";
            //Password to use for SMTP authentication
            $mail->Password = "BOjp0Grhi9E1pmUT41k3Ra/Wfu5cC1Ad7xGh14ta6dGd";
            //Set who the message is to be sent from
            $mail->setFrom($data['email_from'], $data['email_from_name']);

            //Set who the message is to be sent to
            $mail->addAddress($data['email_to'], $data['email_to_name']);
            $mail->isHTML(true);
            //Set the subject line
            $mail->Subject = $data['subject'];

            $e_data['site_settings']=getSiteSettings();
            $e_data['content']=$data;
            $eMessage= view('emails.'.$template,$e_data);
            // pr($eMessage);
            $mail->Body = $eMessage;
            //Replace the plain text body with one created manually
            // $mail->AltBody = 'This is a plain-text message body';

            //send the message, check for errors
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
                return false;

            } else {
                return true;
            }

        } catch (\Exception $e) {
             echo ("Message could not be sent. Error >> ".$e->getMessage());
             return false;
        }
}
function addLog($logFile){
    Log::useDailyFiles(storage_path().'/logs/'.$logFile);
}
function sendMsg($data){
    $params=array(
        'credentials'=>array(
            'key'=>'AKIAT6A63I5ATUNC5NH3',
            'secret'=>'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',
        ),
        'region'=>'us-east-1',
        'version'=>'latest'
    );
    $sns=new \Aws\Sns\SnsClient($params);
    $args=array(
        'MessageAttributes'=>[
            'AWS.SNS.SMS.SenderID' => [
                'DataType' => 'String',
                'StringValue' => 'LIVELOFTUS',
            ],
            'AWS.SNS.SMS.SMSType'=>[
                'DataType'=>'String',
                'StringValue'=>'Promotional'
            ]
            ],
            'Message'=>$data['message'],
            'PhoneNumber'=>$data['receiver']
    );
    $result=$sns->publish($args);
    // pr($result);
    if(!empty($result['MessageId'])){
        return true;
    }
    else{
        return false;
    }
}
function createPinPointApp(){
    $settings=(array(
        'credentials' => [
            'key'=>'AKIAT6A63I5ATUNC5NH3',
            'secret'=>'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',
        ],
        'region' => 'us-east-1',
        'version'  => 'latest',
    ));

    $client=new \Aws\Pinpoint\PinpointClient($settings);
    // pr($client);
    $result = $client->createApp([
        'CreateApplicationRequest' => [ // REQUIRED
            'Name' => 'Loftus Pinpoint Application', // REQUIRED
            // 'tags' => ['loftus'],
        ],
    ]);
    if(!empty($result['ApplicationResponse']['Id'])){
        return $result['ApplicationResponse']['Id'];
    }
}
function sendOTP($phone,$reference){
    // $app_id=createPinPointApp();
    $settings=(array(
        'credentials' => [
            'key'=>'AKIAT6A63I5ATUNC5NH3',
            'secret'=>'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',
        ],
        'region' => 'us-east-1',
        'version'  => 'latest',
    ));

    $client=new \Aws\Pinpoint\PinpointClient($settings);
    $result = $client->sendOTPMessage([
        'ApplicationId' => '3807462cc34649f8bdaf7581870e94ef', // REQUIRED
        'SendOTPMessageRequestParameters' => [ // REQUIRED
            'BrandName' => 'Loftus', // REQUIRED
            'Channel' => 'SMS', // REQUIRED
            'CodeLength' => 6,
            'DestinationIdentity' => $phone, // REQUIRED
            'OriginationIdentity' => '+18334432151', // REQUIRED
            'ReferenceId' => $reference, // REQUIRED
            // 'ValidityPeriod'=>3
        ],
    ]);
    if($result['MessageResponse']['Result'][$phone]['StatusCode']==200){
        return $result['MessageResponse']['Result'][$phone]['MessageId'];
    }
    else{
        return false;
    }
}
function verifyOtp($otp,$phone,$reference){
    $settings=(array(
        'credentials' => [
            'key'=>'AKIAT6A63I5ATUNC5NH3',
            'secret'=>'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',
        ],
        'region' => 'us-east-1',
        'version'  => 'latest',
    ));

    $client=new \Aws\Pinpoint\PinpointClient($settings);
    $result = $client->verifyOTPMessage([
        'ApplicationId' => '3807462cc34649f8bdaf7581870e94ef', // REQUIRED
        'VerifyOTPMessageRequestParameters' => [ // REQUIRED
            'DestinationIdentity' => $phone, // REQUIRED
            'Otp' => $otp, // REQUIRED
            'ReferenceId' => $reference, // REQUIRED
        ],
    ]);
    if(!empty($result['VerificationResponse']['Valid']) && $result['VerificationResponse']['Valid']==1){
        return true;
    }
    else{
        return false;
    }
}
function getSiteSettings(){
    return Admin::where('id','=',1)->first();
}
function get_branch_size($id){
    if(intval($id) > 0 && $row= DB::table('branches')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->lot_size;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_listing_floor_plan($id){
    if(intval($id) > 0 && $row= DB::table('branches')->where('id', $id)->first()){
        if(!empty($row)){
            return get_floor_plan_name($row->floor_plan);
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_branch_address($id){
    if(intval($id) > 0 && $row= DB::table('branches')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->address;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_amenity_name($id){
    if(intval($id) > 0 && $row= DB::table('amenties')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->title;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_faq_category_name($id){
    if(intval($id) > 0 && $row= DB::table('faq_categories')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->name;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_category_name($id){
    if(intval($id) > 0 && $row= DB::table('categories')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->name;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_country_states($country_id){
    $rows=DB::table('states')->where('country_id', $country_id)->get();
    return $rows;
}
function get_cat_faqs($category){
    $options = "";
    $rows=DB::table('faqs')->where(['category' => $category,'status' => 1])->get();
    return $rows;
}
function table_count($table,$where){
    $count=DB::table($table)->where($where)->count();
    if(!empty($count) && $count > 0){
        return '<span class="badge badge-light-danger">'.$count.'</span>';
    }
}
function get_site_settings(){
    return Admin::where('id','=',1)->first();
}
function convertArrayMessageToString($array){
    $messages='';
    if(!empty($array)){
        foreach($array as $item){
            $messages.=$item;
        }
    }
    return $messages;
}
function getWebsiteSocialLinks(){
    $social_links=array();
    $facebook=(object)[];
    $instagram=(object)[];
    $discord=(object)[];
    $twitter=(object)[];
    $email=(object)[];
    //Social Links
    $site_settings=get_site_settings();
    $facebook->id=1;
    $facebook->link=$site_settings->site_facebook;
    $facebook->image=config('app.react_url').'/images/social-facebook.svg';
    $social_links[] = $facebook;
    //Instagram
    $instagram->id=2;
    $instagram->link=$site_settings->site_instagram;
    $instagram->image=config('app.react_url').'/images/social-instagram.svg';
    $social_links[] = $instagram;
    //Twitter
    $twitter->id=3;
    $twitter->link=$site_settings->site_twitter;
    $twitter->image=config('app.react_url').'/images/social-twitter.svg';
    $social_links[] = $twitter;
    //Discord
    $discord->id=4;
    $discord->link=$site_settings->site_discord;
    $discord->image=config('app.react_url').'/images/social-discord.svg';
    $social_links[] = $discord;
    //Email
    $email->id=5;
    $email->link=$site_settings->site_email;
    $email->image=config('app.react_url').'/images/social-email.svg';
    $social_links[] = $email;
    return $social_links;
}
function get_state_name($id){
    if(intval($id) > 0 && $row= DB::table('states')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->name;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function get_state_code($id){
    if(intval($id) > 0 && $row= DB::table('states')->where('id', $id)->first()){
        if(!empty($row)){
            return $row->code;
        }
        else{
            return 'N/A';
        }
    }
    else{
        return 'N/A';
    }
}
function getStatus($status){
    if($status==1){
        return '<div class="flex items-center sm:justify-center text-theme-9"> <i data-feather="check-square" class="w-4 h-4 mr-2"></i> Active </div>';
    }
    else{
        return '<div class="flex items-center sm:justify-center text-theme-6"> <i data-feather="check-square" class="w-4 h-4 mr-2"></i> Inactive </div>';
    }
}
function getTenantStatus($status){
    if($status==1){
        return '<span class="badge green">Complete</span>';
    }
    else{
        return '<span class="badge yellow">Incomplete</span>';
    }
}
function getTenantReportStatus($expiry_date){
    if(strtotime($expiry_date) >= strtotime(date('Y-m-d'))){
        return '<span class="badge green">Received</span>';
    }
    else{
        return '<span class="badge red">Expired</span>';
    }
}
function getLandlordReportExpiryDate($screeningRequestRenterId,$type){
    if($type=='IdReport'){
        $report= DB::table('offer_tenants')
        ->select('offer_tenant_reports.expiry_date')
        ->join('offer_tenant_reports','offer_tenant_reports.tenant_id','=','offer_tenants.id')
        ->where(['offer_tenants.screeningRequestRenterId' => intval($screeningRequestRenterId)])
        ->get()->first();
    }
    else{
        $report= DB::table('offer_tenants')
        ->select('offer_tenant_reports.expiry_date')
        ->join('offer_tenant_reports','offer_tenant_reports.tenant_id','=','offer_tenants.id')
        ->where(['offer_tenants.screeningRequestRenterId' => intval($screeningRequestRenterId), 'offer_tenant_reports.type' => $type])
        ->get()->first();
    }

    if(!empty($report)){
        return getTenantReportStatus($report->expiry_date);
    }
    else{
        return 'N/A';
    }
}
function getLandlordReportExpiryDateFlag($screeningRequestRenterId,$type){
    if($type=='IdReport'){
        $report= DB::table('offer_tenants')
        ->select('offer_tenant_reports.expiry_date')
        ->join('offer_tenant_reports','offer_tenant_reports.tenant_id','=','offer_tenants.id')
        ->where(['offer_tenants.screeningRequestRenterId' => $screeningRequestRenterId])
        ->get()->first();
    }
    else{
        $report= DB::table('offer_tenants')
        ->select('offer_tenant_reports.expiry_date')
        ->join('offer_tenant_reports','offer_tenant_reports.tenant_id','=','offer_tenants.id')
        ->where(['offer_tenants.screeningRequestRenterId' => $screeningRequestRenterId, 'offer_tenant_reports.type' => $type])
        ->get()->first();
    }
    if(!empty($report)){
        return getTenantReportStatusFlag($report->expiry_date);
    }
    else{
        return 'N/A';
    }
}
function getTenantReportStatusFlag($expiry_date){
    if(strtotime($expiry_date) >= strtotime(date('Y-m-d'))){
        return true;
    }
    else{
        return false;
    }
}
function getOfferStatus($offer_status,$tenants_unpaid_count){
    if($tenants_unpaid_count>0){
        return '<span class="badge yellow">Incomplete</span>';
    }
    else if($offer_status=='accepted'){
        return '<span class="badge green">Accepted</span>';
    }
    else if($offer_status=='rejected'){
        return '<span class="badge red">Rejected</span>';
    }
    else{
        return '<span class="badge yellow">Pending</span>';
    }
}
function getReadStatus($status){
    if($status==1){
        return '<span class="badge badge-success">Read</span>';
    }
    else{
        return '<span class="badge badge-danger">Unread</span>';
    }
}
function getApproveStatus($status){
    if($status=='1'){
        return '<span class="badge badge-success">Approved</span>';
    }
    else if($status=='2'){
        return '<span class="badge badge-warning">Denied</span>';
    }
    else if($status=='3'){
        return '<span class="badge badge-danger">Cancelled</span>';
    }
    else{
        return '<span class="badge badge-secondary">Pending</span>';
    }
}
function getFeatured($status){
    if($status==1){
        return '<span class="badge badge-success">Yes</span>';
    }
    else{
        return '<span class="badge badge-danger">Not</span>';
    }
}
function userAccountType($type){
    if(!empty($type)){
        return '<span class="badge badge-danger"><i class="fa fa-google-plus-square"></i> Google</span>';
    }
    else{
        return '<span class="badge badge-info"><i class="fa fa-user"></i> Normal</span>';
    }
}
function get_page($key){
    $row=Sitecontent::where('ckey',$key)->first();
    return unserialize($row->code);
}
function get_blog_tags(){
    $keywords=Blog_model::pluck('meta_keywords');
    $tags='';
    foreach($keywords as $key=>$keyword){
        $tags.=strtolower($keyword);
    }
    $meta=explode(",",rtrim($tags,","));
    $blog_tags=[];
    foreach($meta as $mt){
        $blog_tags[]=trim($mt);
    }
    return array_unique($blog_tags);
}
function time_ago($time)
{
    $time = str_replace('/', '-', $time);
    // pr($time);
    $timestamp = (is_numeric($time) && (int)$time == $time ) ? $time : strtotime($time);

    // $strTime = array("second", "minute", "hour", "day", "month", "year");
    $strTime = array(" sec", " min", " hr", " day", " month", " year");
    $length = array("60", "60", "24", "30", "12", "10");

    $currentTime = time();
    if($currentTime >= $timestamp) {
        $diff     = $currentTime- $timestamp;
        for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
            $diff = $diff / $length[$i];
        }
        $diff = round($diff);
        if($strTime[$i] == 'y' || $strTime[$i] == 'y')
            return date('M d, Y h:i a', $timestamp);
        $ago = $diff > 1 ? ' ago' : ' ago';
        $ago = $diff > 1 ? 's ago' : ' ago';
        // return $diff .$strTime[$i];
        if($diff==1&&$strTime[$i]==' day')
            return 'yesterday';
        return $diff . $strTime[$i] .$ago;
    }
    else{

    }
}
function format_date($d, $format = '', $default_show = 'TBD')
{
    $format = empty($format) ? 'm/d/Y' : $format;
    // $d = str_replace('/', '-', $d);
    if($d=='0000:00:00' || $d=='0000-00-00' || !$d)
        return $default_show;
    $d = (is_numeric($d) && (int)$d == $d ) ? $d : strtotime($d);
    return date($format, $d);
}
function convertDateToTimeZone($timestamp,$timezone){ /* input: 1518404518,America/Los_Angeles */
        $date = new DateTime(date("d F Y H:i:s",$timestamp));
        $date->setTimezone(new DateTimeZone($timezone));
        $rt=$date->format('Y-m-d'); /* output: Feb 11, 2018 7:01:58 pm */
        return $rt;
    }
function toSlugUrl($text)
{

    $text = trim($text);
    $text = str_replace("&quot", '', $text);
    $text = preg_replace('/[^A-Za-z0-9-]+/', '-', $text);
    $text = str_replace("--", '-', $text);
    $text = str_replace("--", '-', $text);
    $text = str_replace("@", '-', $text);
    return strtolower($text);
}
function short_text($str, $length = 150)
{
    $str = strip_tags($str);
    return strlen($str) > $length ? substr($str, 0, $length).'...' : $str;
}
function countEndingDigits($string)
{
    $tailing_number_digits =  0;
    $i = 0;
    $from_end = -1;
    while ($i < strlen($string)) :
      if (is_numeric(substr($string, $from_end - $i, 1))) :
        $tailing_number_digits++;
      else :
        // End our while if we don't find a number anymore
        break;
      endif;
      $i++;
    endwhile;
    return $tailing_number_digits;
}
function getData($table,$where){
    if(empty($table)){
        $table='faqs';
    }
    $rows=DB::table($table)->where($where)->get();
    return $rows;
}
function getSingleData($table,$where){
    if(empty($table)){
        $table='faqs';
    }
    $rows=DB::table($table)->where($where)->get()->first();
    return $rows;
}

function get_pages()
    {
        return $page_arr = array('/'=>'Home','/about'=>'About Us','/contact'=>'Contact Us','/become-youtuber'=>'Become a Youtuber','/help'=>'Help','/login'=>'Sign In','/signup'=>'Sign Up','/faq'=>'FAQs','/login'=>'Login','/signup'=>'Signup','/search'=>'Explore','/privacy-policy'=>'Privacy Policy','/terms-conditions'=>'Terms & Conditions','/forgot-password'=>'Forget Password','/reset-password'=>'Reset Password');
    }
function checkSlug($slug,$table_name) {

    if(DB::Table($table_name)->where('slug',$slug)->count() > 0){
     $numInUN = countEndingDigits($slug);
     if ($numInUN > 0) {
              $base_portion = substr($slug, 0, -$numInUN);
              $digits_portion = abs(substr($slug, -$numInUN));
      } else {
              $base_portion = $slug . "-";
              $digits_portion = 0;
      }

      $slug = $base_portion . intval($digits_portion + 1);
      $slug = checkSlug($slug,$table_name);
    }

    return $slug;
}



?>

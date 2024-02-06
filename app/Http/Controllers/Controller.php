<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Listing_model;
use App\Models\Member_model;
use App\Models\Offer_model;
use App\Models\Offer_tenant_model;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        $this->data['site_settings']=$this->getSiteSettings();

        $this->data['enable_editor']=false;
        $this->data['all_pages']=get_pages();

    }

    public function checkNewAccountOffers($member){
        $offers_arr=Offer_model::orderBy('id', 'DESC')->where(['offer_tenants.email'=>$member->mem_email,'offers.deleted'=>0])->rightJoin('offer_tenants', 'offer_tenants.offer_id', '=', 'offers.id')->get(['offers.*']);
        if(!empty($offers_arr)){
            foreach($offers_arr as $offer){
                $offer_tenant= Offer_tenant_model::where(['offer_id'=>$offer->id,'email'=>$member->mem_email])->update(["mem_id" => $member->id]);

            }
        }
    }
    public function expireListingEmail(){
        $expired_listing_arr=Listing_model::where(['listing.mem_type'=>'member','listing.listing_status'=>'active','properties.deleted'=>0])->where('listing.expires_in','>=',date("Y-m-d"))->leftJoin('properties', 'properties.id', '=', 'listing.property')->join('members', 'members.id', '=', 'listing.mem_id')
                        ->get(['listing.*', 'properties.formatted_address', 'properties.slug', 'members.mem_fname', 'members.mem_email']);
        if(!empty($expired_listing_arr)){
            foreach($expired_listing_arr as $expired){
                if(date("Y-m-d") >= date("Y-m-d",strtotime($expired->expires_in))){
                    if($expired->listing_type=='rent'){
                        $url=config('app.react_url')."/rent-listing/".$expired->slug;
                    }
                    else if($expired->listing_type=='sale'){
                        $url=config('app.react_url')."/sale-listing/".$expired->slug;
                    }
                    else{
                         $url=config('app.react_url')."/listing";
                    }
                    $email_data=array(
                        'email_to'=>$expired->mem_email,
                        'email_to_name'=>$expired->mem_fname,
                        'listing_type'=>$expired->listing_type,
                        // 'expiry_date'=>format_date(add_days($input['expires_in'],date('Y-m-d')),'F j, Y'),
                        'property_address'=>format_address_single($expired->formatted_address),
                        'email_from'=>'noreply@liveloftus.com',
                        'email_from_name'=>$this->data['site_settings']->site_name,
                        'subject'=>'Listing Expired',
                        'link'=>$url,
                    );
                    send_email($email_data,'listing_expired');
                }
                else{
                    return false;
                }
            }
        }
    }

    public function currentLanguage(){
        if(Session()->has("site_lang") && session("site_lang")=='en'){
            return 'English';
        }
        else{
            return 'Spanish';
        }
    }
    public function create_mem_token($mem_id){
        $member=Member_model::where(['id' => $mem_id])->get()->first();
        if($member->id > 0){
            $token=$member->id."-".$member->mem_email."-".$member->mem_type."-".rand(99,999);
            $userToken=encrypt_string($token);
            $token_array=array(
                'mem_type'=>$member->mem_type,
                'token'=>$userToken,
                'mem_id'=>$member->id,
                'expiry_date'=>date("Y-m-d", strtotime("6 months")),
            );
            DB::table('tokens')->insert($token_array);
            return $userToken;
        }
        return false;
        
    }
    public function getSiteSettings($lang=''){
        if(Session::get('site_lang')){
            $site_lang=Session::get('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(!empty($lang)){
            $site_lang=$lang;
        }
        return Admin::where('site_lang','=',$site_lang)->get()->first();
    }
    public function getMember($mem_id,$mem_email){
        return Member_model::where(['id' => $mem_id,'mem_email'=>$mem_email])->get()->first();
    }
    public function authenticate_verify_token($token){
        if(!empty($token) && $userToken= DB::table('tokens')->where('token', $token)->first()){
            $toke_expiry = date('Y-m-d',strtotime($userToken->expiry_date));
            if(strtotime($toke_expiry)<=strtotime(date('Y-m-d'))){
                return false;
            }
            else{
                $token_parts=decrypt_string($userToken->token);
                $token_array=explode("-",$token_parts);
                $member=$this->getMember($token_array[0],$token_array[1]);
                return $member;
                if(!empty($member)){
                    $this->checkNewAccountOffers($member);
                    return $member;
                }
                else{
                    return false;
                }
            }
        }
    }
    public function authenticate_verify_email_token($token){
        if(!empty($token) && $userToken= DB::table('tokens')->where('token', $token)->first()){
            $toke_expiry = date('Y-m-d H:i:s',strtotime($userToken->expiry_date));
            if(strtotime($toke_expiry)<=strtotime(date('Y-m-d'))){
                return false;
            }
            else{
                $token_parts=decrypt_string($userToken->token);
                $token_array=explode("-",$token_parts);
                $member=$this->getMember($token_array[0],$token_array[1]);
                if(!empty($member)){
                    return $member;
                }
                else{
                    return false;
                }
            }
        }
    }
}

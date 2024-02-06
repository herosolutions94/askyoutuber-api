<?php

namespace App\Http\Controllers\admin;

use App\Models\Member_model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Members extends Controller
{
    public function index(){
        $this->data['rows']=Member_model::where('mem_type', 'member')->orderByDesc("id")->get();
        return view('admin.members.index',$this->data);
    }
    public function add(Request $request){

        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('mem_image')) {

                $request->validate([
                    'mem_image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $mem_image=$request->file('mem_image')->store('public/members/');
                if(!empty(basename($mem_image))){
                    $data['mem_image']=basename($mem_image);
                }

            }
            if(!empty($input['mem_status'])){
                $data['mem_status']=1;
            }
            else{
                $data['mem_status']=0;
            }
            if(!empty($input['mem_verified'])){
                $data['mem_verified']=1;
            }
            else{
                $data['mem_verified']=0;
            }
            if(!empty($input['mem_email_verified'])){
                $data['mem_email_verified']=1;
            }
            else{
                $data['mem_email_verified']=0;
            }
            $data['mem_type']='member';
            $data['mem_fname']=$input['mem_fname'];
            $data['mem_mname']=$input['mem_mname'];
            $data['mem_lname']=$input['mem_lname'];
            $data['mem_email']=$input['mem_email'];
            $data['mem_phone']=$input['mem_phone'];
            $data['mem_dob']=date('d-m-Y',strtotime($input['mem_dob']));
            $data['mem_bio']=$input['mem_bio'];
            $data['mem_address1']=$input['mem_address1'];
            $data['mem_address2']=$input['mem_address2'];
            $data['mem_city']=$input['mem_city'];
            $data['mem_country']=$input['mem_country'];
            $data['mem_state']=$input['mem_state'];
            $data['mem_zip']=$input['mem_zip'];
            if(!empty($input['mem_password'])){
                $data['mem_password']=md5($input['mem_password']);
            }
            // pr($data);
            $id = Member_model::create($data);
            return redirect('admin/members/')
                ->with('success','Content Updated Successfully');
        }
        $this->data['enable_editor']=true;
        return view('admin.members.index',$this->data);
    }
    public function edit(Request $request, $id){

        $member=Member_model::where(['id' => $id,'mem_type' => 'member'])->get()->first();
        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('mem_image')) {

                $request->validate([
                    'mem_image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $mem_image=$request->file('mem_image')->store('public/members/');
                if(!empty($mem_image)){
                    $member->mem_image=basename($mem_image);
                }

            }

            if(!empty($input['mem_status'])){
                $member->mem_status=1;
            }
            else{
                $member->mem_status=0;
            }
            if(!empty($input['mem_verified'])){
                $member->mem_verified=1;
            }
            else{
                $member->mem_verified=0;
            }
            if(!empty($input['mem_email_verified'])){
                $member->mem_email_verified=1;
            }
            else{
                $member->mem_email_verified=0;
            }
            $member->mem_type='member';
            $member->mem_fname=$input['mem_fname'];
            $member->mem_mname=isset($input['mem_mname']);
            $member->mem_lname=$input['mem_lname'];
            // $member->mem_phone=$input['mem_phone'];
            $member->mem_dob=!empty($input['mem_dob']) ? date('d-m-Y',strtotime($input['mem_dob'])) : '';
            $member->mem_bio=$input['mem_bio'];
            $member->mem_address1=isset($input['mem_address1']);
            $member->mem_address2=isset($input['mem_address2']);
            $member->mem_city=isset($input['mem_city']);
            $member->mem_country=$input['mem_country'];
            $member->mem_state=$input['mem_state'];
            $member->mem_zip=$input['mem_zip'];
            if(!empty($input['mem_password'])){
                $member->mem_password=md5($input['mem_password']);
            }
            // pr($data);
            $member->update();
            return redirect('admin/members/edit/'.$request->segment(4))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Member_model::where(['id' => $id,'mem_type' => 'member'])->get()->first();
        $this->data['enable_editor']=true;
        return view('admin.members.index',$this->data);
    }
    public function delete($id){
        $member = Member_model::where(['id' => $id,'mem_type' => 'member'])->get()->first();
        DB::table('branches')->where('mem_id', $id)->delete();
        DB::table('floor_plans')->where('mem_id', $id)->delete();
        DB::table('listing')->where('mem_id', $id)->delete();
        DB::table('member_questions')->where('mem_id', $id)->delete();
        DB::table('notifications')->where('sender', $id)->delete();
        DB::table('offer_tenants')->where('mem_id', $id)->delete();
        DB::table('properties')->where('mem_id', $id)->delete();
        DB::table('tokens')->where('mem_id', $id)->delete();

        // removeImage("members/".$member->mem_image);
        $member->delete();
        return redirect('admin/members/')
                ->with('error','Member deleted Successfully');
    }
}

<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\Partners_model;
use App\Http\Controllers\Controller;

class Partners extends Controller
{
    public function index(){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $this->data['rows']=Partners_model::where('site_lang','=',$site_lang)->get();
        return view('admin.partners.index',$this->data);
    }
    public function add(Request $request){

        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/partners/');
                if(!empty(basename($image))){
                    $data['image']=basename($image);
                }

            }
            if(!empty($input['status'])){
                $data['status']=1;
            }
            else{
                $data['status']=0;
            }
            $data['name']=$input['name'];
            $data['site_lang']=session('site_lang');
            $id = Partners_model::create($data);
            return redirect('admin/partners/')
                ->with('success','Content Updated Successfully');
        }

        return view('admin.partners.index',$this->data);
    }
    public function edit(Request $request, $id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id) > 0 && $partner_row = Partners_model::where('site_lang', '=', $site_lang)->find($id)) {
            $input = $request->all();
            if($input) {
                $data=array();
                if ($request->hasFile('image')) {

                    $request->validate([
                        'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image')->store('public/partners/');
                    if(!empty($image)){
                        $partner_row->image=basename($image);
                    }

                }
                else{
                    $partner_row->image=$partner_row->image;
                }

                if(!empty($input['status'])) {
                    $partner_row->status=1;
                } else {
                    $partner_row->status=0;
                }
                $partner_row->name=$input['name'];

                $partner_row->update();
                return redirect('admin/partners/edit/'.$request->segment(4))
                    ->with('success', 'Content Updated Successfully');
            }
            $this->data['row']=Partners_model::where('site_lang', '=', $site_lang)->find($id);
            return view('admin.partners.index', $this->data);
        }
        else{
            return redirect('admin/partners/')
            ->with('error','Row does not exist');
        }
    }
    public function delete($id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id) > 0 && $partner_row = Partners_model::where('site_lang','=',$site_lang)->find($id)){
            $partner_row->delete();
            return redirect('admin/partners/')
                ->with('success','Content deleted Successfully');
        }
        else{
            return redirect('admin/partners/')
            ->with('error','Row does not exist');
        }

    }
}

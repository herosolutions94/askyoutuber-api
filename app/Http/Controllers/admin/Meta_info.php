<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Meta_info_model;
use Illuminate\Http\Request;

class Meta_info extends Controller
{
    public function index(){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $this->data['rows']=Meta_info_model::where('site_lang','=',$site_lang)->get();
        return view('admin.meta.index',$this->data);
    }
    public function add(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/meta/');
                if(!empty(basename($image))){
                    $data['image']=basename($image);
                }

            }
            if ($request->hasFile('og_image')) {

                $request->validate([
                    'og_image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $og_image=$request->file('og_image')->store('public/meta/');
                if(!empty(basename($og_image))){
                    $data['og_image']=basename($og_image);
                }

            }
            $data['page_name']=$input['page_name'];
            $data['slug']=$input['slug'];
            $data['meta_title']=$input['meta_title'];
            $data['meta_description']=$input['meta_description'];
            $data['meta_keywords']=$input['meta_keywords'];
            $data['og_title']=$input['og_title'];
            $data['og_description']=$input['og_description'];
            $data['site_lang']=session('site_lang');
            // pr($data);
            $id = Meta_info_model::create($data);
            return redirect('admin/meta_info/')
                ->with('success','Content Updated Successfully');
        }
        return view('admin.meta.index',$this->data);
    }
    public function edit(Request $request, $id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id)>0 && $meta_info=Meta_info_model::where('site_lang','=',$site_lang)->find($id)){
            $input = $request->all();
            if($input){
                $data=array();
                if ($request->hasFile('image')) {

                    $request->validate([
                        'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image')->store('public/meta/');
                    if(!empty($image)){
                        $meta_info->image=basename($image);
                    }

                }
                else{
                    $meta_info->image=$meta_info->image;
                }
                if ($request->hasFile('og_image')) {

                    $request->validate([
                        'og_image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $og_image=$request->file('og_image')->store('public/meta/');
                    if(!empty($og_image)){
                        $meta_info->og_image=basename($og_image);
                    }
                }
                else{
                    $meta_info->og_image=$meta_info->og_image;
                }
                $meta_info->page_name=$input['page_name'];
                $meta_info->slug=$input['slug'];
                $meta_info->meta_title=$input['meta_title'];
                $meta_info->meta_description=$input['meta_description'];
                $meta_info->meta_keywords=$input['meta_keywords'];
                $meta_info->og_title=$input['og_title'];
                $meta_info->og_description=$input['og_description'];

                // pr($data);
                $meta_info->update();
                return redirect('admin/meta_info/edit/'.$request->segment(4))
                    ->with('success','Content Updated Successfully');
            }
            $this->data['row']=Meta_info_model::where('site_lang','=',$site_lang)->find($id);
            return view('admin.meta.index',$this->data);
        }
        else{
            return redirect('admin/meta_info/')
                ->with('error','Invalid Request!');
        }
    }
    public function delete($id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id) > 0 && $meta_info = Meta_info_model::where('site_lang','=',$site_lang)->find($id)){
            $meta_info->delete();

            return redirect('admin/meta_info/')
                ->with('error','Content deleted Successfully');
        }
        else{
            return redirect('admin/meta_info/')
                ->with('error','Invalid Request!');
        }
    }
}

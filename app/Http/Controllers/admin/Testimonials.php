<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial_model;
use Illuminate\Http\Request;

class Testimonials extends Controller
{
    public function index(){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $this->data['rows']=Testimonial_model::where('site_lang','=',$site_lang)->get();
        return view('admin.testimonials.index',$this->data);
    }
    public function add(Request $request){

        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/testimonials/');
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
            $data['address']=$input['address'];
            $data['message']=$input['message'];
            $data['site_lang']=session('site_lang');
            // pr($data);
            $id = Testimonial_model::create($data);
            return redirect('admin/testimonials/')
                ->with('success','Content Updated Successfully');
        }
        $this->data['enable_editor']=true;
        return view('admin.testimonials.index',$this->data);
    }
    public function edit(Request $request, $id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id) > 0 && $testimonial=Testimonial_model::where('site_lang', '=', $site_lang)->find($id)){
            $input = $request->all();
            if($input){

                $data=array();
                if ($request->hasFile('image')) {

                    $request->validate([
                        'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image')->store('public/testimonials/');
                    if(!empty($image)){
                        $testimonial->image=basename($image);
                    }

                }
                else{
                    $testimonial->image=$testimonial->image;
                }
                if(!empty($input['status'])){
                    $testimonial->status=1;
                }
                else{
                    $testimonial->status=0;
                }
                $testimonial->name=$input['name'];
                $testimonial->address=$input['address'];
                $testimonial->message=$input['message'];
                $data['site_lang']=session('site_lang');
                // pr($data);
                $testimonial->update();
                return redirect('admin/testimonials/edit/'.$request->segment(4))
                    ->with('success','Content Updated Successfully');
            }
            $this->data['row']=Testimonial_model::where('site_lang', '=', $site_lang)->find($id);
            $this->data['enable_editor']=true;
            return view('admin.testimonials.index',$this->data);
        }
        else{
            return redirect('admin/testimonials/')
                ->with('error','Invalid request!');
        }
    }
    public function delete($id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id) > 0 && $testimonial=Testimonial_model::where('site_lang', '=', $site_lang)->find($id)){
            removeImage("testimonials/".$testimonial->image);
            $testimonial->delete();
            return redirect('admin/testimonials/')
                    ->with('error','Content deleted Successfully');
        }
        else{
            return redirect('admin/testimonials/')
                ->with('error','Invalid request!');
        }
    }
}

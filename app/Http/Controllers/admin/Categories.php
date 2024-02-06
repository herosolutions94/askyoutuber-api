<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Categories_model;
use App\Http\Controllers\Controller;

class Categories extends Controller
{
    public function index(){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $this->data['rows']=Categories_model::where('site_lang','=',$site_lang)->get();
        return view('admin.categories.index',$this->data);
    }
    public function add(Request $request){

        $input = $request->all();
        if($input){
            $data=array();
            if ($request->hasFile('image')) {

                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $image=$request->file('image')->store('public/categories/');
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
            $data['slug'] = checkSlug(Str::slug($data['name'], '-'),'categories');
            $id = Categories_model::create($data);
            return redirect('admin/categories/')
                ->with('success','Content Updated Successfully');
        }

        return view('admin.categories.index',$this->data);
    }
    public function edit(Request $request, $id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id) > 0 && $faq_category = Categories_model::where('site_lang', '=', $site_lang)->find($id)) {
            $input = $request->all();
            if($input) {
                $data=array();
                if ($request->hasFile('image')) {

                    $request->validate([
                        'image' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image')->store('public/categories/');
                    if(!empty($image)){
                        $faq_category->image=basename($image);
                    }

                }
                else{
                    $faq_category->image=$faq_category->image;
                }

                if(!empty($input['status'])) {
                    $faq_category->status=1;
                } else {
                    $faq_category->status=0;
                }
                $faq_category->name=$input['name'];
                $faq_category->slug = checkSlug(Str::slug($faq_category->name, '-'), 'categories');

                $faq_category->update();
                return redirect('admin/categories/edit/'.$request->segment(4))
                    ->with('success', 'Content Updated Successfully');
            }
            $this->data['row']=Categories_model::where('site_lang', '=', $site_lang)->find($id);
            return view('admin.categories.index', $this->data);
        }
        else{
            return redirect('admin/categories/')
            ->with('error','Category does not exist');
        }
    }
    public function delete($id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id) > 0 && $faq_category = Categories_model::where('site_lang','=',$site_lang)->find($id)){
            $faq_category->delete();
            return redirect('admin/categories/')
                ->with('success','Content deleted Successfully');
        }
        else{
            return redirect('admin/categories/')
            ->with('error','Category does not exist');
        }

    }
}

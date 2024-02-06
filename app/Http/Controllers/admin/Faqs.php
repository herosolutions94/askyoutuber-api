<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faq_model;
use App\Models\Faq_categories_model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Faqs extends Controller
{
    public function index(){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $this->data['rows']=Faq_model::where('site_lang','=',$site_lang)->get();
        return view('admin.faq.index',$this->data);
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
            if ($request->hasFile('author_dp')) {

                $request->validate([
                    'author_dp' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                ]);
                $author_dp=$request->file('author_dp')->store('public/faqs/');
                if(!empty(basename($author_dp))){
                    $data['author_dp']=basename($author_dp);
                }

            }
            if(!empty($input['status'])){
                $data['status']=1;
            }
            else{
                $data['status']=0;
            }
            $data['author']=$input['author'];
            $data['question']=$input['question'];
            $data['answer']=$input['answer'];
            $data['category']=$input['category'];
            $data['sub_category']=$input['sub_category'];
            $data['site_lang']=$site_lang;
            $data['slug'] = checkSlug(Str::slug($data['question'], '-'),'faqs');
            // pr($data);
            $id = Faq_model::create($data);
            return redirect('admin/faqs/')
                ->with('success','Content Updated Successfully');
        }
        $this->data['enable_editor']=true;
        $this->data['categories']=Faq_categories_model::where('status', 1)->where('site_lang','=',$site_lang)->where('parent', 0)->get();
        $this->data['sub_categories']=[];
        return view('admin.faq.index',$this->data);
    }
    public function edit(Request $request, $id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id) > 0 && $faq=Faq_model::where('site_lang','=',$site_lang)->find($id)){
            $input = $request->all();
            if($input){
                $data=array();

                if(!empty($input['status'])){
                    $faq->status=1;
                }
                else{
                    $faq->status=0;
                }
                if ($request->hasFile('author_dp')) {
                    $request->validate([
                        'author_dp' => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $author_dp=$request->file('author_dp')->store('public/faqs/');
                    if(!empty($author_dp)){
                        $faq->author_dp=basename($author_dp);
                    }
                }
                else{
                    $faq->author_dp=$faq->author_dp;
                }
                $faq->question=$input['question'];
                $faq->answer=$input['answer'];
                $faq->category=$input['category'];
                $faq->sub_category=$input['sub_category'];
                $faq->author=$input['author'];
                $faq->slug = checkSlug(Str::slug($faq->question, '-'), 'faqs');

                // pr($data);
                $faq->update();
                return redirect('admin/faqs/edit/'.$request->segment(4))
                    ->with('success','Content Updated Successfully');
            }
            $this->data['row']=Faq_model::where('site_lang','=',$site_lang)->find($id);
            $this->data['categories']=Faq_categories_model::where('status', 1)->where('site_lang','=',$site_lang)->where('parent', 0)->get();
            $this->data['sub_categories']=Faq_categories_model::where('status', 1)->where('parent','=',$this->data['row']->category)->where('site_lang','=',$site_lang)->get();
            $this->data['enable_editor']=true;
            return view('admin.faq.index',$this->data);
        }
        else{
            return redirect('admin/faqs/')
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
        if(intval($id) > 0 && $faq = Faq_model::where('site_lang','=',$site_lang)->find($id)){
           $faq->delete();
            return redirect('admin/faqs/')
                ->with('error','Content deleted Successfully');
        }
        else{
            return redirect('admin/faqs/')
                ->with('error','Invalid Request!');
        }

    }
}

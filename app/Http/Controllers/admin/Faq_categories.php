<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Faq_categories_model;
use App\Models\Faq_model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Faq_categories extends Controller
{
    public function index(){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $this->data['rows']=Faq_categories_model::where('site_lang','=',$site_lang)->get();
        return view('admin.faq.category',$this->data);
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
            if(!empty($input['status'])){
                $data['status']=1;
            }
            else{
                $data['status']=0;
            }
            $data['name']=$input['name'];
            $data['parent']=$input['parent'];
            $data['site_lang']=session('site_lang');
            $data['slug'] = checkSlug(Str::slug($data['name'], '-'),'faq_categories');
            // pr($data);
            $id = Faq_categories_model::create($data);
            return redirect('admin/faq_categories/')
                ->with('success','Content Updated Successfully');
        }
        $this->data['categories']=Faq_categories_model::where("parent","=",0)->where('site_lang','=',$site_lang)->get();
        return view('admin.faq.category',$this->data);
    }
    public function edit(Request $request, $id){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id)>0 && $faq_category=Faq_categories_model::where('site_lang','=',$site_lang)->find($id)){
            $input = $request->all();
            if($input){
                $data=array();

                if(!empty($input['status'])){
                    $faq_category->status=1;
                }
                else{
                    $faq_category->status=0;
                }
                $faq_category->name=$input['name'];
                $faq_category->parent=$input['parent'];
                $faq_category->slug = checkSlug(Str::slug($faq_category->name, '-'), 'faq_categories');
                // pr($data);
                $faq_category->update();
                return redirect('admin/faq_categories/edit/'.$request->segment(4))
                    ->with('success','Content Updated Successfully');
            }
            $this->data['categories']=Faq_categories_model::where("parent","=",0)->where('site_lang','=',$site_lang)->get();
            $this->data['row']=Faq_categories_model::where('site_lang','=',$site_lang)->find($id);
            return view('admin.faq.category',$this->data);
        }
        else{
            return redirect('admin/faq_categories/')
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
        if(intval($id) > 0 && $faq_category = Faq_categories_model::where('site_lang','=',$site_lang)->find($id)){
            Faq_model::where(["site_lang"=>$site_lang,'sub_category'=>$faq_category->id])->delete();
            Faq_model::where(["site_lang"=>$site_lang,'category'=>$faq_category->id])->delete();
            $faq_category->delete();

            return redirect('admin/faq_categories/')
                ->with('error','Content deleted Successfully');
        }
        else{
            return redirect('admin/faq_categories/')
                ->with('error','Invalid Request!');
        }
    }
    public function faq_sub_categories($id){
        $res=array();
        $res['status']=0;
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        if(intval($id) > 0 && $categories = Faq_categories_model::where('site_lang','=',$site_lang)->where('parent','=',$id)->get()){
            $html='';
            if(!empty($categories)){
                foreach($categories as $cat){
                    $html.='<option value="'.$cat->id.'">'.$cat->name.'</option>';
                }
            }
            else{
                $html.="<option value=''>No Sub Categories Found!</option>";
            }
            $res['status']=1;
            $res['html']=$html;
        }
        else{
            $res['msg']='Invalid request!';
        }
        exit(json_encode($res));
    }
}

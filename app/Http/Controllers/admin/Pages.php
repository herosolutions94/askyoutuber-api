<?php

namespace App\Http\Controllers\admin;
use App\Models\Sitecontent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Pages extends Controller
{

    public function home(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 7; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_home',$this->data);
    }
    public function about(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 3; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_about',$this->data);
    }
    public function become_a_youtuber(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 5; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_become_a_youtuber',$this->data);
    }
    public function help(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_help',$this->data);
    }
    public function contact(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_contact',$this->data);
    }
    public function signin(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_signin',$this->data);
    }
    public function signup(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_signup',$this->data);
    }
    public function forgot(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_forgot',$this->data);
    }
    public function reset(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_reset',$this->data);
    }
    public function privacy_policy(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_privacy_policy',$this->data);
    }
    public function terms_conditions(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_terms_conditions',$this->data);
    }
    public function search(Request $request){
        if(Session()->has('site_lang')){
            $site_lang=session('site_lang');
        }
        else{
            $site_lang='en';
        }
        $page=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        if(empty($page)){
            $page = new Sitecontent;
            $page->site_lang=$site_lang;
            $page->ckey=$request->segment(3);
            $page->code='';
            $page->save();
        }
        $input = $request->all();
        if($input){
            $content_row = unserialize($page->code);
            if(!is_array($content_row))
                $content_row = array();
            for ($i = 1; $i <= 1; $i++) {
                if ($request->hasFile('image'.$i)) {

                    $request->validate([
                        'image'.$i => 'mimes:png,jpg,jpeg,svg,gif|max:40000'
                    ]);
                    $image=$request->file('image'.$i)->store('public/images/');
                    if(!empty($image)){
                        $input['image'.$i]=basename($image);
                    }

                }

            }
            $data = serialize(array_merge($content_row, $input));
            // pr($input);
            $page->ckey=$request->segment(3);
            $page->code=$data;
            $page->save();
            return redirect('admin/pages/'.$request->segment(3))
                ->with('success','Content Updated Successfully');
        }
        $this->data['row']=Sitecontent::where('ckey',$request->segment(3))->where('site_lang',$site_lang)->first();
        $this->data['sitecontent']=unserialize($this->data['row']->code);
        return view('admin.website_pages.site_search',$this->data);
    }



}

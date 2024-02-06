<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter_model;

class Subscribers extends Controller
{
    public function index(){
        $this->data['rows']=Newsletter_model::all();
        return view('admin.subscribers',$this->data);
    }
    public function delete($id){
        $faq = Newsletter_model::find($id);
        $faq->delete();
        return redirect('admin/subscribers/')
                ->with('error','Message deleted Successfully');
    }
}

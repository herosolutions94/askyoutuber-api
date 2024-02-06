<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact_model;

class Contact extends Controller
{
    public function index(){
        $this->data['rows']=Contact_model::all();
        return view('admin.contact',$this->data);
    }
    public function view($id){
        $contact=Contact_model::find($id);
        $contact->status=1;
        $contact->update();
        $this->data['row']=$contact;
        return view('admin.contact',$this->data);
    }
    public function delete($id){
        $faq = Contact_model::find($id);
        $faq->delete();
        return redirect('admin/contact/')
                ->with('error','Message deleted Successfully');
    }
}

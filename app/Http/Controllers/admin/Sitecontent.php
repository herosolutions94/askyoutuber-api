<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class Sitecontent extends Controller
{

    public function index(){
        // dd(Auth::user()->email);
        return view('admin.sitecontent',$this->data);

    }


}

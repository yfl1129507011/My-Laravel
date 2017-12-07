<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    //首页
    public function home(){
        return view('index/home');
    }

    public function about(){
        return view('index/about');
    }

    public function help(){
        return view('index/help');
    }
}

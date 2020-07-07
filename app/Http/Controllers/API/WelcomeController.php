<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Welcome;

class WelcomeController extends Controller
{
    private $welcome;

    public function __construct(Welcome $welcome)
    {
        $this->welcome = $welcome;
    }

    public function index(){
        try {            
            $blogs_data = $this->welcome->get_blogs();
    
            return $blogs_data;
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }

    public function check_path(){
        if(Auth::check()){
            return view('layouts.admin_layout');
        }
        else{
            return view('welcome');
        }
    }

    public function get_blogs($id){
        try {            
            $blogs_data = $this->welcome->get_blogs_data($id);
            return $blogs_data;
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }

    public function search_blog($search){
        try {            
            $blogs_data = $this->welcome->search_blog($search);
    
            return $blogs_data;
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }
}

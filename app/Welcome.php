<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Welcome extends Model
{
    //
    public function get_blogs(){
        return DB::select('SELECT md5(blog_id) blog_id,blog_title,blog_description,blog_image,created_at 
                        FROM blogs ORDER BY blog_title,created_at ASC');
    }

    //
    public function get_blogs_data($id){
        return DB::select('SELECT md5(blog_id) blog_id,blog_title,blog_description,blog_image,created_at 
                        FROM blogs WHERE md5(concat(blog_id)) = "'.$id.'" ORDER BY blog_title,created_at ASC LIMIT 1');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Blogs extends Model
{
    //
    //table name
    protected $table = 'blogs';
    // primary key 
    public $primaryKey = 'blog_id';
    // timestampts
    public $timestamps = true;

    public function get_blogs(){
        return DB::select('SELECT md5(blog_id) blog_id,blog_title,blog_description,blog_image,created_at 
                        FROM blogs ORDER BY blog_title,created_at ASC');
    }

    public function get_search($search){
        return DB::select('SELECT md5(blog_id) blog_id,blog_title,blog_description,blog_image,created_at 
                        FROM blogs WHERE blog_title LIKE "%'.$search.'%" OR blog_description LIKE "%'.$search.'%"
                        ORDER BY blog_title,created_at ASC');
    }

    public function get_blog($id){
        return DB::select('SELECT * FROM blogs WHERE md5(concat(blog_id)) = "'.$id.'" LIMIT 1');
    }

    public function move_to_recycle_bin($id){
        $data = $this->get_blog($id);
        $insert = DB::insert('INSERT INTO deleted_blogs (db_title,db_description,db_image,db_created,created_at,
                        updated_at) VALUES ("'.$data[0]->blog_title.'","'.$data[0]->blog_description.'",
                        "'.$data[0]->blog_image.'","'.$data[0]->created_at.'",now(),now())
        ');
        if($insert){
            $delete = DB::delete('DELETE FROM blogs WHERE md5(concat(blog_id)) = "'.$id.'"');
            if($delete){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function get_deleted_blogs(){
        return DB::select('SELECT md5(db_id) db_id,db_title,db_description,db_image,db_created,created_at 
                FROM deleted_blogs ORDER BY created_at,db_title ASC');
    }

    public function get_search_deleted_blogs($search){
        return DB::select('SELECT md5(db_id) db_id,db_title,db_description,db_image,db_created,created_at 
                FROM deleted_blogs WHERE db_title LIKE "%'.$search.'%" OR db_description LIKE "%'.$search.'%"
                ORDER BY created_at,db_title ASC');
    }

    public function restore_blog($id){
        $data = DB::select('SELECT * FROM deleted_blogs WHERE md5(concat(db_id)) = "'.$id.'"');
        $insert = DB::insert('INSERT INTO blogs (blog_title,blog_description,blog_image,created_at,updated_at) 
                        VALUES ("'.$data[0]->db_title.'","'.$data[0]->db_description.'",
                        "'.$data[0]->db_image.'","'.$data[0]->db_created.'",now())
        ');
        if($insert){
            $delete = DB::delete('DELETE FROM deleted_blogs WHERE md5(concat(db_id)) = "'.$id.'"');
            if($delete){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function delete_blogs_permanent($id){
        $delete = DB::delete('DELETE FROM deleted_blogs WHERE md5(concat(db_id)) = "'.$id.'"');
        if($delete){
            return true;
        }
        else{
            return false;
        }
    }
}

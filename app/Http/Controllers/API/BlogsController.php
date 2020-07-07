<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Blogs;
use Validator;
use Response;
use Image;

class BlogsController extends Controller
{

    private $blogs;

    public function __construct(Blogs $blogs)
    {
        $this->blogs = $blogs;
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            $blogs_data = $this->blogs->get_blogs();
            return $blogs_data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }  
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $this->validate($request, [
                'blog_title' => 'required|string',
                'blog_description' => 'required',
                'blog_image' => 'required|string',
            ]);        
    
            $request = new Request($request->all()); 
                          
            if($request->blog_image != null){
                if($request->blog_image != 'default.png'){
                    
                    $name = time().'.'.explode('/', explode(':',substr($request->blog_image, 0,strpos
                    ($request->blog_image, ';')))[1])[1];
    
                    \Image::make($request->blog_image)->save(public_path('/images/blog_photo/').$name);
                    $request->merge(['blog_image' => $name]);
    
                }
                else{
                    $request->merge(['blog_image' => 'default.png']);
                }
            }
            else{
                $request->merge(['blog_image' => 'default.png']);
            }
    
            $this->blogs->blog_title = $request->blog_title;
            $this->blogs->blog_description = $request->blog_description;
            $this->blogs->blog_image = $request->blog_image;
            
            if($this->blogs->save()){
                return ['type' => 'success', 'title' => 'Success message','message' => "Blog information created successfully"];
            }
            else{
                return 'Blog cannot be save!';
            } 
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            if($id){
                $blog = $this->blogs->get_search($id);
                return $blog;
            }
            else{
                return 'Invalid search';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $this->validate($request, [
                'blog_title' => 'required|string',
                'blog_description' => 'required',
            ]); 
    
            
            $request = new Request($request->all()); 
            $blog_data = $this->blogs->get_blog($request->blog_id);
    
            $currentPhoto = $blog_data[0]->blog_image;
            if($request->blog_image != null){
                if($request->blog_image != $currentPhoto){
                    $blogimage = time().'.'.explode('/', explode(':',substr($request->blog_image, 0,strpos
                    ($request->blog_image, ';')))[1])[1];
        
                    Image::make($request->blog_image)->save(public_path('/images/blog_photo/').$blogimage);
                    $request->merge(['blog_image' => $blogimage]);
        
                    $blogphoto = public_path('images/blog_photo/').$currentPhoto;
        
                    if(file_exists($blogphoto)){
                        @unlink($blogphoto);
                    }
                }
                else{
                    $request->merge(['blog_image' => $currentPhoto]);
                }    
            }
            else{
                $request->merge(['blog_image' => $currentPhoto]);
            }
    
            $blogs = $this->blogs->find($blog_data[0]->blog_id);
            $blogs->blog_title = $request->blog_title;
            $blogs->blog_description = $request->blog_description;
            $blogs->blog_image = $request->blog_image;
            if($blogs->save()){
                return ['type' => 'success', 'title' => 'Success message','message' => "Blog information updated successfully"];
            }
            else{
                return 'Blog cannot be update!';
            } 
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }  
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $move_to_recycle_bin = $this->blogs->move_to_recycle_bin($id);
                
            if($move_to_recycle_bin){
                return ['type' => 'success', 'title' => 'Success message','message' => "Blog information moved to recycle bin successfully"];
            }
            else{
                return 'Blog cannot move to recycle bin';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }  
            
    }

    // get deleted blogs
    public function get_deleted_blogs(){
        try {            
            $deleted_blogs_data = $this->blogs->get_deleted_blogs();
    
            return $deleted_blogs_data;
        } catch (\Exception $e) {
            return $e->getMessage();
        }  
    }

    // search filter in deleted blogs
    public function search_deleted_blogs($search)
    {
        //
        try {            
            if($search){
                $blog = $this->blogs->get_search_deleted_blogs($search);
                return $blog;
            }
            else{
                return 'Invalid search';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }

    // restore deleted blogs
    public function restore_blogs($id)
    {
        //
        try {            
            $restore_blog = $this->blogs->restore_blog($id);
                
            if($restore_blog){
                return ['type' => 'success', 'title' => 'Success message','message' => "Blog restored successfully"];
            }
            else{
                return 'Blog cannot be restored!';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        } 

    }
    
    // deleted blogs permanently
    public function delete_blogs_permanent($id)
    {
        //

        try {            
            $delete_blogs_permanent = $this->blogs->delete_blogs_permanent($id);
            
            if($delete_blogs_permanent){
                return ['type' => 'success', 'title' => 'Success message','message' => "Blog deleted successfully"];
            }
            else{
                return 'Blog cannot be deleted!';
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        } 

    }
}

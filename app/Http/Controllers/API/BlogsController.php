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
        $blogs_data = $this->blogs->get_blogs();

        return $blogs_data;
        
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
            return 'good';
        }
        else{
            return 'error';
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
        if($id){
            $blog = $this->blogs->get_search($id);
            return $blog;
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
            return 'good';
        }
        else{
            return 'error';
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
        $move_to_recycle_bin = $this->blogs->move_to_recycle_bin($id);
            
        if($move_to_recycle_bin){
            return 'good';
        }
        else{
            return 'error';
        }
            
    }

    // get deleted blogs
    public function get_deleted_blogs(){
        $deleted_blogs_data = $this->blogs->get_deleted_blogs();

        return $deleted_blogs_data;
    }
}

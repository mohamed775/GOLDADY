<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Responces\ResponseHelper;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{

    /*
       - ResponseHelper -> class has many method that handle server response
       - PostResource -> DTO for save DB
    */
    
    // getAll posts

    public function index()
    {
        try{

            $posts = Post::with('user' ,'category')->paginate(10);
            return  ResponseHelper::returnData('posts' , $posts , 'all posts retrived' );  

        }catch(\Exception $e){
           return ResponseHelper::returnServerError($e->getMessage());
        }
    }


    // get specific post by : id

    public function show(string $id)
    {
        try{

            $post = Post::with('user' , 'category')->find($id);

            if($post){
                return ResponseHelper::returnData('post' ,PostResource::make($post) , 'post exist'); 
            } 

            return ResponseHelper::notFound() ;

        }catch(\Exception $e ){
            return ResponseHelper::returnServerError($e->getMessage());
        }
    }


    // Add

    public function store(Request $request)
    {
        try{
            $validate=  Validator::make($request->all(),[
                'user_id' => 'required|int',
                'category_id' => 'required|int',
                'title' => 'required|string|max:255',
                'content' => 'required|string|max:500'
            ]);

            if($validate->fails()){
                return ResponseHelper::validateError( $validate->errors());
            }
    
            $post = Post::create($request->all());

            // Logging
            log::info('Post created: ', $post->toArray());
            
            return response()->json(ResponseHelper::returnData('post' ,PostResource::make($post) , 'post added successfully !') , 201) ; 
    
        }catch(\Exception $e){
            return ResponseHelper::returnServerError($e->getMessage());
        }
    }

    
    //update

    public function update(Request $request, string $id)
    {
        try{

            $post = Post::with('user','category')->find($id);

            if($post){

                $validate = Validator::make($request->all(),[
                    'title' => 'required|string|max:255',
                    'content' => 'required|string|max:500'
                ]);

                if($validate->fails()){
                    return ResponseHelper::validateError( $validate->errors());
                }

                $post->title  = $request->title ;
                $post->content  = $request->content ;

                $post->save();

                // Logging
                Log::info('Post updated: ', $post->toArray());

                return response()->json(ResponseHelper::returnData('post' ,PostResource::make($post) , 'post updated successfully !') , 202) ; 

            } 

            return ResponseHelper::notFound() ;
    
        }catch(\Exception $e){
            return ResponseHelper::returnServerError($e->getMessage());
        }
    }


    //delete

    public function destroy(string $id)
    {
        try{

            $post = Post::find($id);
            if($post){

                $post->delete();

                // Logging
                Log::info('Post deleted: ', $post->toArray());

                return ResponseHelper::returnSuccessMessage('post deleted successfully !'); 
            } 
            return ResponseHelper::notFound() ;
    
        }catch(\Exception $e){
            return ResponseHelper::returnServerError($e->getMessage());
        }
    }
}

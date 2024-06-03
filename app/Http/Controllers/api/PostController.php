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
    
    public function index()
    {
        try{

            $posts = Post::with('user' ,'category')->paginate(10);
            return ResponseHelper::returnData('posts' , $posts , 'all posts retrived' , 200 );  

        }catch(\Exception $e){
           return ResponseHelper::returnError($e->getMessage());
        }
    }

    public function show(string $id)
    {
        try{

            $post = Post::with('user' , 'category')->find($id);

            if($post){
                return ResponseHelper::returnData('post' ,PostResource::make($post) , 'post exist' , 200); 
            } 

            return ResponseHelper::returnError('post not found');

        }catch(\Exception $e ){
            return ResponseHelper::returnError($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try{
            $validate=  Validator::make($request->all(),[
                'user_id' => 'required|int|max:20',
                'category_id' => 'required|int|max:20',
                'title' => 'required|string|max:255',
                'content' => 'required|string|max:500'
            ]);

            if($validate->fails()){
                return ResponseHelper::returnError( $validate->errors());
            }
    
            $post = Post::create($request->all());

            // Logging
            log::info('Post created: ', $post->toArray());
            
            return ResponseHelper::returnData('post' ,PostResource::make($post) , 'post added successfully !' , 201); 

    
        }catch(\Exception $e){
            return ResponseHelper::returnError($e->getMessage());
        }
    }

    
    public function update(Request $request, string $id)
    {
        try{

            $post = Post::with('user' ,'category')->find($id);

            if($post){

                $validate = Validator::make($request->all(),[
                    'title' => 'required|string|max:255',
                    'content' => 'required|string|max:500'
                ]);

                if($validate->fails()){
                    return ResponseHelper::returnError( $validate->errors());
                }

                $post->title  = $request->title ;
                $post->content  = $request->content ;

                $post->save();

                Log::info('Post updated: ', $post->toArray());

                return ResponseHelper::returnData('post' ,PostResource::make($post) , 'post updated successfully !' , 200); 
            } 

            return ResponseHelper::returnError('post not found');
    
        }catch(\Exception $e){
            return ResponseHelper::returnError($e->getMessage());
        }
    }

    
    public function destroy(string $id)
    {
        try{

            $post = Post::find($id);
            if($post){
                $post->delete();
                Log::info('Post deleted: ', $post->toArray());
                return ResponseHelper::returnSuccessMessage('post deleted successfully !' , 200); 
            } 
            return ResponseHelper::returnError('post not found');
    
        }catch(\Exception $e){
            return ResponseHelper::returnError($e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Responces\ResponseHelper;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class PostController extends Controller
{

    /*
       - ResponseHelper -> class has many method that handle server response
       - PostResource -> DTO for save DB
    */
    
    // getAll posts

    public function index(Request $request)
    {
        try{

            $posts = Post::with('user' ,'category')->paginate($request->get('per_page' ,10));  // custom paginate 
            return  PostResource::collection($posts);  

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
            $user = Auth::user(); // Get the authenticated user

            $validate=  Validator::make($request->all(),[
                // 'user_id' => 'required|int',
                'category_id' => 'required|int',
                'title' => 'required|string|max:255',
                'content' => 'required|string|max:500'
            ]);

            if($validate->fails()){
                return ResponseHelper::validateError( $validate->errors());
            }
    
            // Create a new post
            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->user_id = $user->id; // Associate the post with the authenticated user
            $post->category_id = $request->category_id ;
            $post->save();

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

                // Check if the authenticated user is the owner of the post
                if ($post->user_id !== Auth::id()) {
                    return response()->json(['error' => 'You are not authorized to edit this post'], 403);
                }

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

                 // Check if the authenticated user is the owner of the post
                 if ($post->user_id !== Auth::id()) {
                   return response()->json(['error' => 'You are not authorized to delete this post'], 403);
                 }

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

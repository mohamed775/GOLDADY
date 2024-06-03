<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Responces\ResponseHelper;
use App\Models\category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    
    public function index()
    {
        try{

            $categories = category::select()->paginate(10);
            return ResponseHelper::returnData('categories' , CategoryResource::collection($categories)  , 'all categories retrived' , 200 );  

        }catch(\Exception $e){
           return ResponseHelper::returnError($e->getMessage());
        }
        
    }


    public function show(string $id)
    {
        try{

            $category = category::find($id);

            if($category){
                return ResponseHelper::returnData('category' ,CategoryResource::make($category) , 'category found' , 200); 
            } 

            return ResponseHelper::returnError('category not found');

        }catch(\Exception $e ){
            return ResponseHelper::returnError($e->getMessage());
        }
        
    }


    public function store(Request $request)
    {
        try{
            $validate=  Validator::make($request->all(),['name' => 'required|string|max:255|unique:categories']);

            if($validate->fails()){
                return ResponseHelper::returnError( $validate->errors());
            }
    
            $category = category::create($request->all());
            
            return ResponseHelper::returnData('category' ,CategoryResource::make($category) , 'category added successfully !' , 201); 

    
        }catch(\Exception $e){
            return ResponseHelper::returnError($e->getMessage());
        }

    }


    
    public function update(Request $request, string $id)
    {
        try{

            $category = category::find($id);

            if($category){

                $validate = Validator::make($request->all(),[
                    'name' => 'required|string|max:255|unique:categories'
                ]);

                if($validate->fails()){
                    return ResponseHelper::returnError( $validate->errors());
                }

                $category->name  = $request->name ;
                $category->save();

                return ResponseHelper::returnData('category' ,CategoryResource::make($category) , 'category updated successfully !' , 200); 
            } 

            return ResponseHelper::returnError('category not found');
    
        }catch(\Exception $e){
            return ResponseHelper::returnError($e->getMessage());
        }
    }

    
    public function destroy(string $id)
    {
        try{

            $category = category::find($id);
            if($category){
                $category->delete();
                return ResponseHelper::returnSuccessMessage('category deleted successfully !' , 200); 
            } 
            return ResponseHelper::returnError('category not found');
    
        }catch(\Exception $e){
            return ResponseHelper::returnError($e->getMessage());
        }
    }
}

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

    /*
      - ResponseHelper -> class has many method that handle server response 
      - CategoryResource -> DTO for save  DB
    */
    

    // getAll categories

    public function index(Request $request)
    {
        try{

            $categories = category::paginate($request->get('per_page',10));  // coustom paginate with default = 10 
            return CategoryResource::collection($categories);  

        }catch(\Exception $e){
           return ResponseHelper::returnServerError($e->getMessage());
        }
        
    }


    // get specific category by : id

    public function show(string $id)
    {
        try{

            $category = category::find($id);

            if($category){
                return ResponseHelper::returnData('category' ,CategoryResource::make($category) , 'category exist'); 
            } 

            return ResponseHelper::notFound();

        }catch(\Exception $e ){
            return ResponseHelper::returnServerError($e->getMessage());
        }
        
    }


    // add 

    public function store(Request $request)
    {
        try{

            $validate = Validator::make($request->all(),['name' => 'required|string|max:255|unique:categories']);

            if($validate->fails()){ 
                return ResponseHelper::validateError( $validate->errors());
            }
    
            $category = category::create($request->all());
            
            return response()->json(ResponseHelper::returnData('category' ,CategoryResource::make($category) , 'category added successfully !') , 201) ; 

    
        }catch(\Exception $e){
            return ResponseHelper::returnServerError($e->getMessage());
        }

    }


    // update 

    public function update(Request $request, string $id)
    {
        try{

            $category = category::find($id);

            if($category){

                $validate = Validator::make($request->all(),[
                    'name' => 'required|string|max:255|unique:categories'
                ]);

                if($validate->fails()){
                    return ResponseHelper::validateError( $validate->errors());
                }

                $category->name  = $request->name ;
                $category->save();

                return response()->json(ResponseHelper::returnData('category' ,CategoryResource::make($category) , 'category updated successfully !') , 202) ; 

            } 

            return ResponseHelper::notFound() ;
    
        }catch(\Exception $e){
            return ResponseHelper::returnServerError($e->getMessage());
        }
    }

    // delete 

    public function destroy(string $id)
    {
        try{

            $category = category::find($id);
            
            if($category){
                $category->delete();
                return ResponseHelper::returnSuccessMessage('category deleted successfully !'); 
            } 
            return ResponseHelper::notFound() ;
    
        }catch(\Exception $e){
            return ResponseHelper::returnServerError($e->getMessage());
        }
    }
}

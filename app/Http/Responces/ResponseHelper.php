<?php
namespace App\Http\Responces ;

use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class ResponseHelper
{

   /* ----  coustom server response   ---- */


    //  not found

    public static function notFound()
    {
        return response()->json([
            'status' => false ,
            'message' => 'not found'
        ] ,404);
     }

     //  validate error

     public static function validateError($msg)
     {
        return response()->json([
            'status' => false ,
            'message' => $msg 
        ] , 422);
     }

          
     //  server error

     public static function returnServerError($msg)
     {
        return response()->json([
            'status' => false ,
            'message' => $msg 
        ] , 500 );
     }
 

     //  success msg

     public static function returnSuccessMessage($msg ="" )
     {
        return response()->json([
            'status' => true ,
            'message' => $msg ,
        ],200);

     }


     // return data

     public static function returnData($key , $value , $msg=""   ){

        return response()->json([
            'status' => true ,
            'message' => $msg ,
             $key => $value,
        ],200);

     }


     // user not auth
     
     public static function notAuthenticated()
     {
        return response()->json([
            'status' => false ,
            'message' => 'user not authenticated' ,
        ],401);

     }

}

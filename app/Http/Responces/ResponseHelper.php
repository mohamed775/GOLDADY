<?php
namespace App\Http\Responces ;

use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class ResponseHelper
{

     public function getCurrentLang(){
        return app()->getLocale();
     }

     public static function returnError($msg)
     {
        return response()->json([
            'status' => false ,
            'message' => $msg 
        ]);
     }
 
     public static function returnSuccessMessage($msg ="" , $code)
     {
        return response()->json([
            'status' => true ,
            'message' => $msg ,
            'code' => $code
        ]);

     }

     public static function returnData($key , $value , $msg="" , $code  ){

        return response()->json([
            'status' => true ,
            'message' => $msg ,
             $key => $value,
            'codeStatus' => $code ,
        ]);

     }

    

}

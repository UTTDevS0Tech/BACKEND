<?php

namespace App\Traits;

trait ApiResponse
{
    

      public function apiResponse($data, $message = null, $code = 200, $error = null)
    {
        return response()->json([
            'status' => 'success',
            'error' => $error,
            'data' => $data,
            'message' => $message,
        ], $code);
    }
     protected function successResponse($data=null, string $message='operacion exitosa uwu', int $code=200){

        return $this->apiResponse($data, $message, $code);
     }

        protected function errorResponse(string $message= 'ocurrió un error', int $code=400, $error=null){
    
            return $this->apiResponse(null, $message, $code, $error);
        }


}



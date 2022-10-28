<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\CommonTrait;
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests, CommonTrait, FileUploadTrait;

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($response, $message=null)
    {
        $response['status'] = 'success';
        $response['success'] = true;;
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'stausCode'=>$code,
            'status'=>'error',
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, 200);
    }
}

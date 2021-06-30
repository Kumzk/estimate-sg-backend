<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



    /**
     *成功時にレスポンス
     * 
     * @param  array  $data
     * @return \Illuminate\Http\Response
     */
    public function successResponse($data = null)
    {   
        if (isset($data)){
            return response()->json(['data' => $data,'success' => true], 200);
        }
        return response()->json(['success' => true], 200);
    }

    /**
     *失敗時のレスポンス
     * 
     * @return \Illuminate\Http\Response
     */
    public function errorResponse()
    {   
        return response()->json(['message' => "Invalid request",'success' => false], 400);
    }
}

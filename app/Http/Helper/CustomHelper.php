<?php

namespace App\Http\Helper;

trait CustomHelper
{

    protected function returnResponse($check = [], $successMessage = 'Successfully', $faildMessage = "Something is wrong")
    {
        if (count($check)) {
            return response()->json([
                'status' => 'pass',
                'message' => $successMessage,
                'data' => $check,
            ]);
        } else {
            return response()->json([
                "status" => "faild",
                "message" => "$faildMessage"
            ]);
        }
    }
}

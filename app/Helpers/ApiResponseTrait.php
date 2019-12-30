<?php

namespace App\Helpers;

trait ApiResponseTrait
{
    protected static function result($code, $message, $data = null)
    {
        $response = [
            'status' => $code,
            'message' => $message,
            'data' => $data
        ];

        if (is_null($data))
            unset($response['data']);

        return response()->json($response, $code);
    }

    protected static function badData($message = "Bad data")
    {
        return self::result(400, $message);
    }

    protected static function serverError($message)
    {
        return self::result(503, $message);
    }

    protected static function success($message = "Success", $data = null)
    {
        return self::result(200, $message, $data);
    }

    protected static function created($message = "Successfully Stored")
    {
        return self::result(201, $message);
    }
}

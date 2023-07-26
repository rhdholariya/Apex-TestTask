<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ResponseBuilder
{
    public static function success(
        $data = null,
        $api_code = 200
    ): HttpResponse {
        return response()->json([
            'success' => true,
            'code' => (int)$api_code,
            'message' => 'OK',
            'data' => $data
        ],$api_code);
    }

    public static function error(
        $message = 'Error',
        int $api_code = 400,
        $data = null,
        int $http_code = 400,
    ): HttpResponse {
        return response()->json([
            'success' => false,
            'code' => (int)$api_code,
            'message' => $message,
            'data' => $data
        ], $http_code);
    }
    public static function unauthorized() : HttpResponse {
        return response()->json([
            'success' => false,
            'code' => 403,
            'message' => ['Unauthorized'],
            'data' => null
        ], 403);
    }

    public static function dataNotFound() : HttpResponse {
        return response()->json([
            'success' => false,
            'code' => 404,
            'message' => 'Error',
            'data' => 'Data not found'
        ], 404);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function response(?array $response): JsonResponse
    {
        $data = [];
        $data['status'] = $response['status'] ?? true;

        if (isset($response['data'])) $data['data'] = $response['data'];
        if (isset($response['message'])) $data['message'] = $response['message'];

        return response()->json($data, $response['code'] ?? ResponseAlias::HTTP_OK);
    }

    public function success(string $message, array $data = [], int $statusCode = Response::HTTP_OK) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    public function error(string $message, int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY) : JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}

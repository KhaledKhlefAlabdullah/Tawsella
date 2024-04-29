<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

/**
 * Prepares a standardized JSON response for API endpoints.
 *
 * @param mixed $data (optional) The data to be included in the response body.
 * @param string $message (optional) A message to be included in the response body.
 * @param int $code (optional) The HTTP status code for the response. Defaults to 200 (OK).
 * @param mixed $pagination (optional) Pagination information to be included in the response.
 * @param array $meta (optional) Additional metadata to be included in the response.
 * @param array $errors (optional) An array of error messages to be included in the response.
 * @return JsonResponse
 *
 * @throws \InvalidArgumentException if an invalid HTTP status code is provided.
 */
if (!function_exists('api_response')) {
    function api_response($data = null, $message = "", $code = 200, $pagination = null, $meta = [], $errors = []): JsonResponse
    {
        $response = [
            'message' => __($message),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($pagination) {
            $response['pagination'] = $pagination;
        }

        if ($meta) {
            $response['meta'] = $meta;
        }

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}

/**
 * @return string token
 */
if (!function_exists('createUserToken')) {
    function createUserToken($user, $token_name)
    {

        // Set the expiration time for the new token
        $expiresAt = Carbon::now()->addDays(30);

        // Create a new token for the user
        $token = $user->createToken($token_name, ['*'], $expiresAt);

        return $token->plainTextToken;
    }
}

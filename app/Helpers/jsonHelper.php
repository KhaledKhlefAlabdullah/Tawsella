<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;


if (!function_exists('api_response')) {
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
    function api_response($data = null, $message = "", $code = 200, $pagination = [], $meta = [], $errors = []): JsonResponse
    {
        $response = [
            'message' => __($message),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if (!empty($pagination)) {
            $response['pagination'] = $pagination;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}

if (!function_exists('get_pagination')) {
    /**
     * Generate pagination metadata from a paginated instance.
     *
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @return array
     */
    function get_pagination($paginator, $request)
    {
        // Get filters from request (assuming they are sent as JSON)
        $filters = json_decode($request->query('Filter.Conditions', '[]'), true);

        // If filters are not provided, default to an empty array
        if (!is_array($filters)) {
            $filters = [];
        }

        return [
            'totalCount' => $paginator->total(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'queryPayload' => [
                'pageNumber' => $paginator->currentPage(),
                'pageSize' => $paginator->perPage(),
                'totalCount' => $paginator->total(),
                'sort' => [
                    'sortBy' => $request->query('Sort.SortBy', 'Id'), // Default sort field
                    'ascending' => $request->query('Sort.Ascending', 'true') === 'true' // Boolean check
                ],
                'filter' => [
                    'logic' => $request->query('Filter.Logic', 'AND'), // Default logic for filters
                    'conditions' => array_map(function ($filter) {
                        return [
                            'field' => $filter['field'], // Field to filter on
                            'operator' => $filter['operator'], // Comparison operator
                            'value' => $filter['value'] // Value to compare against
                        ];
                    }, $filters)
                ]
            ]
        ];
    }

}

if (!function_exists('createUserToken')) {
    /**
     * @param \App\Models\User $user is the user whill make token for it
     * @param string $token_name is the user token name
     * @return string token
     */
    function createUserToken($user, $token_name)
    {

        // Set the expiration time for the new token
        $expiresAt = Carbon::now()->addDays(30);

        // Create a new token for the user
        $token = $user->createToken($token_name, ['*'], $expiresAt);

        return $token->plainTextToken;
    }
}

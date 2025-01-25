<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaginationService
{
    /**
     * Apply filters to the query.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function applyFilters(Builder $query, Request $request): Builder
    {
        // Log the entire query string for debugging
        Log::info('Request Query:', $request->query());

        // Read Filter_Conditions instead of Filter.Conditions
        $filterConditions = $request->query('Filter_Conditions', []);

        Log::info('Filter Conditions:', $filterConditions);

        if (!empty($filterConditions)) {
            foreach ($filterConditions as $condition) {
                $field = $condition['field'] ?? null;
                $operator = $this->validateOperator($condition['operator'] ?? '=');
                $value = $condition['value'] ?? null;

                if ($field && $value !== null) {
                    if (is_array($value)) {
                        $query->whereIn($field, $value);
                    } else {
                        $query->where($field, $operator, $value);
                    }
                }
            }
        }

        return $query;
    }

    /**
     * Validate the operator.
     *
     * @param string $operator
     * @return string
     */
    protected function validateOperator(string $operator): string
    {
        $validOperators = ['=', '!=', '>', '<', '>=', '<=', 'like', 'in', 'not in'];
        return in_array($operator, $validOperators) ? $operator : '=';
    }

    /**
     * Apply sorting to the query.
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function applySorting(Builder $query, Request $request): Builder
    {
        $sortBy = $request->query('Sort.SortBy', 'created_at');
        $ascending = $request->query('Sort.Ascending', 'true') === 'true';

        Log::info('Sorting:', ['SortBy' => $sortBy, 'Ascending' => $ascending]);

        // Validate that the sort column exists in the table
        if (in_array($sortBy, $query->getModel()->getFillable())) {
            return $query->orderBy($sortBy, $ascending ? 'asc' : 'desc');
        }

        return $query;
    }

    /**
     * Paginate the query.
     *
     * @param Builder $query
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate(Builder $query, Request $request)
    {
        $pageNumber = (int) $request->query('PageNumber', 1);
        $pageSize = (int) $request->query('PageSize', 50);

        return $query->paginate($pageSize, ['*'], 'page', $pageNumber);
    }

    /**
     * Apply pagination, filters, and sorting to the query.
     *
     * @param Builder $query
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function applyPagination(Builder $query, Request $request)
    {
        $query = $this->applyFilters($query, $request);
        $query = $this->applySorting($query, $request);
        return $this->paginate($query, $request);
    }
}

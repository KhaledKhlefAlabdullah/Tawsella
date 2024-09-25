<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PaginationService
{
    public function applyFilters(Builder $query, Request $request): Builder
    {
        $filterConditions = json_decode($request->query('Filter.Conditions', '[]'), true);
        if (!empty($filterConditions)) {
            foreach ($filterConditions as $condition) {
                $field = $condition['field'];
                $operator = $condition['operator'];
                $value = $condition['value'];

                // Apply filtering based on the operator
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, $operator, $value);
                }
            }
        }

        return $query;
    }

    public function applySorting(Builder $query, Request $request): Builder
    {
        $sortBy = $request->query('Sort.SortBy', 'Id');
        $ascending = $request->query('Sort.Ascending', true) === 'true';

        return $query->orderBy($sortBy, $ascending ? 'asc' : 'desc');
    }

    public function paginate(Builder $query, Request $request)
    {
        $pageNumber = (int) $request->query('PageNumber', 1);
        $pageSize = (int) $request->query('PageSize', 50);

        return $query->paginate($pageSize, ['*'], 'PageNumber', $pageNumber);
    }
}

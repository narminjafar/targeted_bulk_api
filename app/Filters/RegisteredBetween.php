<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class RegisteredBetween
{
    public function handle(Builder $query, Closure $next)
    {
        $filters = request('filters', []);
        if (!empty($filters['registered_between']) && is_array($filters['registered_between'])) {
            $query->whereBetween('created_at', $filters['registered_between']);
        }
        return $next($query);
    }
}

<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class MarketingOptIn
{
    public function handle(Builder $query, Closure $next)
    {
        $filters = request('filters', []);
        if (isset($filters['marketing_opt_in'])) {
            $query->where('marketing_opt_in', $filters['marketing_opt_in']);
        }
        return $next($query);
    }
}

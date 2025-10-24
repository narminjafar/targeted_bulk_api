<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class LastActive
{
    public function handle(Builder $query, Closure $next)
    {
        $filters = request('filters', []);
        if (!empty($filters['last_active_days'])) {
            $query->where('last_active_at', '>=', now()->subDays($filters['last_active_days']));
        }
        return $next($query);
    }
}

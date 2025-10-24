<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class EmailVerified
{
    public function handle(Builder $query, Closure $next)
    {
        $value = data_get(request('filters', []), 'email_verified');

        $query->when($value === true, fn(Builder $q) => $q->whereNotNull('email_verified_at'))
            ->when($value === false, fn(Builder $q) => $q->whereNull('email_verified_at'));

        return $next($query);
    }
}

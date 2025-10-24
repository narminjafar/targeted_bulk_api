<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class RelationFilter
{
    public string $relationType;

    public function __construct(string $relationType)
    {
        $this->relationType = $relationType;
    }

    public function handle(Builder $query, Closure $next)
    {
        $filters = request('filters', []);
        $value = is_array($filters) ? ($filters[$this->relationType] ?? []) : [];

        if (!empty($value) && is_array($value)) {
            $query->whereHas('products', function (Builder $q2) use ($value) {
                $q2->where('product_users.relation_type', $this->relationType)
                    ->when(isset($value['category']), fn($q3) => $q3->whereHas('category', fn($qc) => $qc->where('slug', $value['category'])))
                    ->when(isset($value['stock_below']), fn($q3) => $q3->where('products.stock', '<=', $value['stock_below']))
                    ->when(isset($value['is_active']), fn($q3) => $q3->where('products.is_active', $value['is_active']))
                    ->when(isset($value['price_between']), fn($q3) => $q3->whereBetween('products.price', $value['price_between']));
            });
        }

        return $next($query);
    }
}

<?php

namespace App\Services;

use App\Filters\EmailVerified;
use App\Filters\MarketingOptIn;
use App\Filters\LastActive;
use App\Filters\RegisteredBetween;
use App\Filters\RelationFilter;

class SegmentFilterService
{
    public array $relationTypes = ['purchased', 'wishlisted', 'subscribed'];

    public function getFilters(): array
    {
        $filters = [
            EmailVerified::class,
            MarketingOptIn::class,
            LastActive::class,
            RegisteredBetween::class,
        ];

        foreach ($this->relationTypes as $relation) {
            $filters[] = new RelationFilter($relation);
        }

        return $filters;
    }
}

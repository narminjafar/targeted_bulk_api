<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SegmentFilterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

class FilterAndRateLimitTest extends TestCase
{
    use RefreshDatabase;

    public function test_filter_builder_returns_query()
    {
        $filterService = new SegmentFilterService();
        $filters = $filterService->getFilters();

        $this->assertIsArray($filters);
        $this->assertNotEmpty($filters);
    }

    public function test_rate_limiter_hits_and_releases()
    {
        $key = 'test_rate';
        RateLimiter::clear($key);

        $this->assertFalse(RateLimiter::tooManyAttempts($key, 1));
        RateLimiter::hit($key, 60);
        $this->assertTrue(RateLimiter::tooManyAttempts($key, 1));
    }
}

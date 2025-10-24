<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Users\UserRepository;
use App\Repositories\Users\UserRepositoryInterface as UsersUserRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;

class RateLimitHelperTest extends TestCase
{
    use RefreshDatabase;

    private UsersUserRepositoryInterface $userRepo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepo = new UserRepository(new \App\Models\User());
    }

    public function test_rate_limiter_blocks_after_limit()
    {
        $key = 'email_send_rate_test';

        for ($i = 0; $i < 10; $i++) {
            RateLimiter::hit($key, 60);
        }

        $this->assertTrue(RateLimiter::tooManyAttempts($key, 10));
    }
}

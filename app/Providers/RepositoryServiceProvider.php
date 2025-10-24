<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Users\UserRepositoryInterface;
use App\Repositories\Users\UserRepository;

use App\Repositories\Campaigns\CampaignRepositoryInterface;
use App\Repositories\Campaigns\CampaignRepository;

use App\Repositories\Segments\SegmentRepositoryInterface;
use App\Repositories\Segments\SegmentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);
        $this->app->bind(SegmentRepositoryInterface::class, SegmentRepository::class);
    }
}

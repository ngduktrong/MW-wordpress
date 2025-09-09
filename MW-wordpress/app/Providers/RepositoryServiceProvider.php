<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\PhimRepositoryInterface;
use App\Repositories\Eloquent\PhimRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PhimRepositoryInterface::class, PhimRepository::class);
    }

    public function boot()
    {
        //
    }
}
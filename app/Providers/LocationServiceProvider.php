<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\LocationRepository;
use App\Repositories\LocationRepositoryInterface;

class LocationServiceProvider extends ServiceProvider
{

    /**
     * Register service
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            LocationRepositoryInterface::class, LocationRepository::class
        );
    }

}
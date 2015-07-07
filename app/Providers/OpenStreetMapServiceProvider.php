<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\OpenStreetMapRepository;
use App\Repositories\OpenStreetMapRepositoryInterface;

class OpenStreetMapServiceProvider extends ServiceProvider
{

    /**
     * Register service
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            OpenStreetMapRepositoryInterface::class,
            OpenStreetMapRepository::class
        );
    }

}
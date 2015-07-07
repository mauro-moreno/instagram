<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\LocationRepositoryInterface;
use App\Repositories\OpenStreetMapRepositoryInterface;
use Vinkla\Instagram\InstagramManager;
use App\Location;

class MediaControllerTest extends TestCase
{

    // Location Interface
    private $location;
    // Street Map Interface
    private $street_map;
    // Instagram Interface
    private $instagram;

    use DatabaseMigrations;

    /**
     * Set up mock objects
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Mock Location Interface
        $mockLocation = \Mockery::mock(
            LocationRepositoryInterface::class
        );
        $this->app->instance(
            LocationRepositoryInterface::class,
            $mockLocation
        );

        // Mock Street Map Interface
        $mockStreetMAp = \Mockery::mock(
            OpenStreetMapRepositoryInterface::class
        );
        $this->app->instance(
            OpenStreetMapRepositoryInterface::class,
            $mockStreetMAp
        );

        // Mock Instagram Interface
        $mockInstagram = \Mockery::mock(
            InstagramManager::class
        );
        $this->app->instance(
            InstagramManager::class,
            $mockInstagram
        );

        // Mock Objects
        $this->location   = $mockLocation;
        $this->street_map = $mockStreetMAp;
        $this->instagram  = $mockInstagram;
    }

    /**
     * Test Media UUID Not found.
     *
     * @return void
     */
    public function testNotFound()
    {
        $instagram = ['meta' => ['code' => 400]];

        $this->location->shouldReceive('findByMediaUUID')
            ->once()
            ->andReturnNull();

        $this->instagram->shouldReceive('getMedia')
            ->once()
            ->andReturn(
                json_decode(json_encode($instagram))
            );

        $this->get('/media/asdf');

        $this->assertResponseStatus(404);
    }

    /**
     * Test Location found in locations by Media UUID
     *
     * @return void
     */
    public function testLocationFound()
    {
        $location = factory(Location::class)->create();

        $this->location->shouldReceive('findByMediaUUID')
            ->once()
            ->andReturn($location);

        $this->get('/media/' . $location->uuid)
            ->seeJson([
                'uuid' => $location->uuid
            ]);

        $this->assertResponseOk();
    }

    /**
     * Test Location not found, found media in Instagram without geopoint
     *
     * @return void
     */
    public function testLocationNotFoundInstagramFoundWithoutGeopoint()
    {
        $instagram = [
            'meta' => [
                'code' => 200
            ],
            'data' => [
                'location' => null
            ]
        ];
        $location = factory(Location::class)->create(
            ['geopoint' => null]
        );

        $this->location->shouldReceive('findByMediaUUID')
            ->once()
            ->andReturnNull();

        $this->instagram->shouldReceive('getMedia')
            ->once()
            ->andReturn(
                json_decode(json_encode($instagram))
            );

        $this->location->shouldReceive('create')
            ->once()
            ->andReturn($location);

        $this->get('/media/12345');
    }

    /**
     * Test Location not found, found media in Instagram with geopoint
     *
     * @return void
     */
    public function testLocationNotFoundInstagramFoundWithLocation()
    {
        $instagram = [
            'meta' => [
                'code' => 200
            ],
            'data' => [
                'location' => [
                    'latitude'  => 1.000,
                    'longitude' => 1.000
                ]
            ]
        ];
        $location = factory(Location::class)->create();

        $this->location->shouldReceive('findByMediaUUID')
            ->once()
            ->andReturnNull();

        $this->instagram->shouldReceive('getMedia')
            ->once()
            ->andReturn(
                json_decode(json_encode($instagram))
            );

        $this->location->shouldReceive('create')
            ->once()
            ->andReturn($location);

        $this->street_map->shouldReceive('getLocationByGeopoint')
            ->once();

        $this->get('/media/12345');
    }

}

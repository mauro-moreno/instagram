<?php

$factory->define(App\Location::class, function ($faker) {
    return [
        'uuid' => $faker->uuid,
        'geopoint' => $faker->randomFloat(6, -90, 90) . ',' . $faker->randomFloat(6, -90, 90)
    ];
});

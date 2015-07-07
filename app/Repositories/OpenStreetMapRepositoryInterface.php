<?php namespace App\Repositories;

interface OpenStreetMapRepositoryInterface
{

    // Get location by geopoint
    public function getLocationByGeopoint($geopoint);

}
<?php namespace App\Repositories;

interface LocationRepositoryInterface
{

    // Find by Media UUID
    public function findByMediaUUID($media_uuid);

    // Create a Location
    public function create($data);

}
<?php namespace App\Repositories;

use App\Location;

class LocationRepository implements LocationRepositoryInterface
{

    /**
     * Find by Instagram Media UUID
     *
     * @param $media_uuid
     * @return mixed
     */
    public function findByMediaUUID($media_uuid)
    {
        $location = Location::where('uuid', $media_uuid)->first();

        return $location;
    }

    /**
     * Create a new Location
     *
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $location = Location::create($this->cleanData($data));

        return $location;
    }

    /**
     * Clean data
     *
     * @param $data
     * @return array $clean_data
     */
    private function cleanData($data)
    {
        $clean_data = [];
        $clean_data['uuid'] = $data->id;
        if ($data->location !== null) {
            $clean_data['geopoint'] = $data->location->latitude
                . ',' . $data->location->longitude;
        }
        return $clean_data;
    }

}
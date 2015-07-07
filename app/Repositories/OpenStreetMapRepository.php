<?php namespace App\Repositories;

class OpenStreetMapRepository implements OpenStreetMapRepositoryInterface
{

    /**
     * Get location by geopoint
     *
     * @param $geopoint
     * @return mixed
     */
    public function getLocationByGeopoint($geopoint)
    {
        $url = 'http://nominatim.openstreetmap.org/reverse?format=json' .
            '&lat=' . $geopoint->latitude . '&lon=' . $geopoint->longitude;
        $raw_data = file_get_contents($url);
        $data = json_decode(utf8_encode($raw_data));
        $data->license = null;
        return $data;
    }

}
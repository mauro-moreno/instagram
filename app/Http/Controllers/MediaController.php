<?php namespace App\Http\Controllers;

use App\Repositories\LocationRepositoryInterface;
use App\Repositories\OpenStreetMapRepositoryInterface;
use Vinkla\Instagram\InstagramManager;

class MediaController extends Controller
{
    // Location repository
    private $location;
    // Open Street Map repository
    private $street_map;
    // Instagram manager
    private $instagram;

    /**
     * Inversion of Container
     *
     * @param LocationRepositoryInterface      $location
     * @param OpenStreetMapRepositoryInterface $street_map
     * @param InstagramManager                 $instagram
     */
    public function __construct(
        LocationRepositoryInterface $location,
        OpenStreetMapRepositoryInterface $street_map,
        InstagramManager $instagram
    )
    {
        $this->location  = $location;
        $this->street_map = $street_map;
        $this->instagram = $instagram;
    }

    /**
     * Index Action
     *
     * @param $mediaID
     *
     * @return null
     */
    public function index($mediaID)
    {
        $return = null;

        // Find by media uuid on location database.
        $location = $this->location->findByMediaUUID($mediaID);

        // If location already in database.
        if ($location !== null) {
            $return = $location;
        } else {
            // Find in Instagram.
            $instagram = $this->instagram->getMedia($mediaID);

            // If found in Instagram
            if ($instagram->meta->code === 200) {
                // Create a new location
                $location = $this->location->create($instagram->data);

                // If Location has geopoint
                if ($location->geopoint !== null) {
                    // Find geopoint reference in instagram
                    $reference = $this->street_map
                        ->getLocationByGeopoint($location->geopoint);

                    // Update reference with OpenStreetMap data
                    $location->reference = json_encode(
                        $reference,
                        JSON_UNESCAPED_UNICODE
                    );
                    $location->save();
                }
                $return = $location;
            }
        }

        // If found or created.
        if ($return !== null) {
            // If reference exists
            if ($return->reference) {
                $return->reference = json_decode(utf8_decode($return->reference));
            }
            return response()->json($return);
        }

        abort(404);
    }

}
<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    // Fillables
    protected $fillable = ['uuid', 'geopoint', 'reference'];
    // Filter geofields
    protected $geofields = ['geopoint'];

    /**
     * Set geopoint attribute, use Point
     *
     * @param $value
     */
    public function setGeopointAttribute($value)
    {
        if (getenv('APP_ENV') !== 'testing') {
            if ($value !== null) {
                $this->attributes['geopoint'] = \DB::raw("POINT($value)");
            }
        } else {
            $this->attributes['geopoint'] = $value;
        }
    }

    /**
     * Get geopoint attribute
     *
     * @param $value
     * @return string
     */
    public function getGeopointAttribute($value)
    {
        $geopoint = null;

        if ($value !== null) {
            $loc = substr($value, 6);
            $loc = preg_replace('/[ ,]+/', ',', $loc, 1);
            $loc = explode(',', substr($loc, 0, -1));

            $geopoint = new \stdClass();
            $geopoint->latitude = $loc[0];
            $geopoint->longitude = $loc[1];
        }

        return $geopoint;
    }

    /**
     * Rewrite query for location.
     *
     * @param bool $excludeDeleted
     * @return mixed
     */
    public function newQuery($excludeDeleted = true)
    {
        $raw = '';
        foreach($this->geofields as $column){
            $raw .= ' astext(' . $column . ') as ' . $column . ' ';
        }

        return parent::newQuery($excludeDeleted)
            ->addSelect('*', \DB::raw($raw));
    }
}
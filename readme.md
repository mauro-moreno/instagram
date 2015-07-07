# Location

## [GET] /media/{instagram_uuid}

* 404 Not found: When location not found on database, and it doesn't exists
on Instagram.
*
```
{
    "id": 1,
    "uuid": "1022569602097622988_312042314",
    "reference": {
        "place_id": "62606031",
        "licence": "Data © OpenStreetMap contributors, ODbL 1.0. http://www.openstreetmap.org/copyright",
        "osm_type": "way",
        "osm_id": "33762764",
        "lat": "19.3673007",
        "lon": "-99.224163",
        "display_name": "Estudiantina, La Martinica, Álvaro Obregón, Ciudad de México, Distrito Federal, 01408, Estados Unidos Mexicanos",
        "address": {
            "road": "Estudiantina",
            "neighbourhood": "La Martinica",
            "city_district": "Álvaro Obregón",
            "city": "Ciudad de México",
            "state": "Distrito Federal",
            "postcode": "01408",
            "country": "Estados Unidos Mexicanos",
            "country_code": "mx"
        },
        "license": null
    },
    "created_at": "2015-07-07 10:05:49",
    "updated_at": "2015-07-07 10:05:50",
    "geopoint": {
        "latitude": "19.367265898",
        "longitude": "-99.223643991"
    }
}
```

## Location Flow

1. Look for __location__ by media UUID on database.
1. If doesn't exists:
    1. Find its data on __Instagram API__
    1. Insert data on _locations_ database.
    1. If location on __Instagram API__ is not null:
        1. Find its place data on OpenStreetMap.
        1. If place data is not null.
            1. Update __location__ with new data.
1. If exists:
    1. Return Media Data as JSON
1. Otherwise return: _404. Not found_.

## Installation

1. Requirements
```
phpunit
composer
mysql
sqlite
```
1. Install vendors ```composer install```
1. Copy .env.example to .env and update with local data ```cp .env.example .env```
1. Update _config/instagram.php_ with a valid client id and client secret.
1. Migrate database ```php artisan migrate```

## TODOs

1. Save extra data.
1. Reference is stored as BLOB could be dynamic columns.
1. Security.
1. Better Class for OpenStreetMap
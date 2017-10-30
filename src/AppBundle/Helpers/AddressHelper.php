<?php

namespace AppBundle\Helpers;

class AddressHelper
{
    public static function validate($address)
    {
        $address = urlencode($address);

        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&sensor=false';

        $geocode = file_get_contents($url);

        $results = json_decode($geocode, true);

        if (empty($results) || $results['status'] != 'OK') {
            return false;
        }

        return $results;
    }
}
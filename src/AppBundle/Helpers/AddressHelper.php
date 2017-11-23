<?php

namespace AppBundle\Helpers;

use AppBundle\Entity\Address;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddressHelper
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * AddressHelper constructor.
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     */
    public function __construct(ValidatorInterface $validator, TranslatorInterface $translator)
    {
        $this->validator = $validator;
        $this->translator = $translator;
    }

    public function validateGoogleMaps($address = '', $lat = '', $lon = '')
    {
        if (empty($address) && empty($lat) && empty($lon)) {
            return false;
        }

        $url = '';
        $api = 'https://maps.googleapis.com/maps/api/geocode/json?region=BR';

        if (!empty($address)) {

            $address = urlencode($address);
            $url = $api . '&address=' . $address . '';

        } else if (!empty($lat) && !empty($lon)) {

            $url = $api . '&latlng=' . $lat . ',' . $lon . '';
        }

        $geocode = file_get_contents($url);

        $results = json_decode($geocode, true);

        if (empty($results) || $results['status'] != 'OK') {
            return false;
        }

        return $results;
    }

    public function getStringErrorsFromEntity(Address $address)
    {
        /** @var ConstraintViolationList $validate */
        $validate = $this->validator->validate($address);

        $output = '';

        if (count($validate) > 0) {
            $errors = $validate->getIterator()->getArrayCopy();
            $translator = $this->translator;

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {
                $output .= '- ' . $translator->trans('address.' . $error->getPropertyPath()) . chr(10);
            }
        }

        return empty($output) ? false : $output;
    }
}
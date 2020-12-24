<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class SanitizeData
{
    public function fullSanitize ($value)
    {
        $value = trim($value);
        $value = mb_strtolower($value);
        $value = str_replace(" ", "", $value);
        $value = $this->reformatCara($value);

        return $value;
    }

    public function reformatCara ($data)
    {

        $spe = array(' ','é','ê','è','à','ô','ï','ä', 'ö', 'ë', '<','>', '\'');
        $noSpe = array('-','e','e','e','a','o','i','a', 'o', 'e', '-','-', '');

        return str_replace($spe, $noSpe, $data);
    }

    public function updateValue ($value, $newValue)
    {
        if($newValue != "" && $newValue != null){
            return $newValue;
        }

        return $value;
    }

    public function createDateFromString($date, $timezone="Europe/Paris"): \DateTime
    {
        try {
            $date = new \DateTime($date);
        } catch (\Exception $e) {
            throw new BadRequestException("Erreur dans la création de la date.");
        }
        $date->setTimezone(new \DateTimeZone($timezone));

        return $date;
    }
}
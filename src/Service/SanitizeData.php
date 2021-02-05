<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class SanitizeData
{
    public function fullSanitize($value)
    {
        $value = trim($value);
        $value = mb_strtolower($value);
        $value = str_replace(" ", "", $value);
        $value = $this->reformatCara($value);

        return $value;
    }

    public function reformatCara($data)
    {

        $spe = array(' ', '<', '>', '\'', 'é', 'è', 'ê', 'ë', 'á', 'ä', 'à', 'â', 'î', 'ï', 'ö', 'ô', 'ù', 'û',
                     'É', 'È', 'Ê', 'Ë', 'À', 'Â', 'Á', 'Î', 'Ï', 'Ô', 'Ù', 'Û', 'ç','Ç');
        $noSpe = array('-', '-', '-', '', 'e','e','e','e','á','a','a','a','i','i','o','o','u','u',
                     'E','E','E','E','A','A','A','I','I','O','U','U','c','C');

        return str_replace($spe, $noSpe, $data);
    }

    public function updateValue($value, $newValue)
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

    public function sanitizeString($value): ?string
    {
        if($value != "" && $value != null){
            $value = trim($value);
            $value = htmlspecialchars($value);

            return $value;
        }

        return null;
    }
}
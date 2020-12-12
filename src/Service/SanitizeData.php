<?php


namespace App\Service;


class SanitizeData
{
    public function fullSanitize ($value)
    {
        $value = trim($value);
        $value = str_replace(" ", "", $value);
        $value = $this->reformatCara($value);
        $value = mb_strtolower($value);

        return $value;
    }

    public function reformatCara ($data)
    {

        $spe = array(' ','é','ê','è','à','ô','ï','ä', 'ö', 'ë', '<','>');
        $noSpe = array('-','e','e','e','a','o','i','a', 'o', 'e', '-','-');

        return str_replace($spe, $noSpe, $data);
    }

    public function updateValue ($value, $newValue)
    {
        if($newValue != "" && $newValue != null){
            return $newValue;
        }

        return $value;
    }
}
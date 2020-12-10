<?php


namespace App\Service;


class CheckerValue
{
    public function getValueOrNull($val)
    {
        if(!isset($val)) {
            return null;
        }

        return $val;
    }
}
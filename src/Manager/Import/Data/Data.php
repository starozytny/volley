<?php

namespace App\Manager\Import\Data;


interface Data
{
    public function __construct($type, $data);
}

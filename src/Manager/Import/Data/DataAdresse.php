<?php

namespace App\Manager\Import\Data;

use App\Manager\Import\DataSanitize;

class DataAdresse extends DataSanitize implements Data
{
    protected $cp;
    protected $ville;
    protected $adr;
    protected $quartier;
    protected $ardt;
    protected $lat;
    protected $lon;

    public function __construct($type, $data)
    {
        if($type == 0){
            $this->cp           = $data[4];
            $this->ville        = $data[5];
            $this->adr          = $data[7];
            $this->quartier     = $data[8];
            $this->lat          = $data[297];
            $this->lon          = $data[298];
        }elseif($type == 1){
            $this->cp           = $data->CP_OFFRE;
            $this->ville        = $data->VILLE_OFFRE;
            $this->adr          = $data->ADRESSE1_OFFRE;
            $this->quartier     = $data->QUARTIER;
            $this->lat          = 0;
            $this->lon          = 0;
        }else{
            $this->cp           = $data['city']['zipcode'] ? $data['city']['zipcode'] : '13510';
            $this->ville        = $data['city']['name'];
            $this->adr          = $data['address'];
            $this->quartier     = $data['district']['name'];
            $this->lat          = $data['latitude'];
            $this->lon          = $data['longitude'];
        }

        $this->setVille($this->capitalize($this->ville));
        $this->setVille($this->deleteNumeric($this->ville));
        $this->setAdr($this->setToNullIfEmpty($this->adr));
        $this->setQuartier($this->setToNullIfEmpty($this->quartier));

        $this->setArdt($this->postalCodeConvert($this->cp, $this->ville));

    }

    /**
     * @return mixed
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @param mixed $cp
     */
    public function setCp($cp): void
    {
        $this->cp = $cp;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     */
    public function setVille($ville): void
    {
        $this->ville = $ville;
    }

    /**
     * @return mixed
     */
    public function getAdr()
    {
        return $this->adr;
    }

    /**
     * @param mixed $adr
     */
    public function setAdr($adr): void
    {
        $this->adr = $adr;
    }

    /**
     * @return mixed
     */
    public function getQuartier()
    {
        return $this->quartier;
    }

    /**
     * @param mixed $quartier
     */
    public function setQuartier($quartier): void
    {
        $this->quartier = $quartier;
    }

    /**
     * @return mixed
     */
    public function getArdt()
    {
        return $this->ardt;
    }

    /**
     * @param mixed $ardt
     */
    public function setArdt($ardt): void
    {
        $this->ardt = $ardt;
    }

    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * @param mixed $lon
     */
    public function setLon($lon): void
    {
        $this->lon = $lon;
    }
}

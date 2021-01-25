<?php

namespace App\Manager\Import\Data;

use App\Manager\Import\DataSanitize;

class DataDiagnostic extends DataSanitize implements Data
{

    protected $dpeval;
    protected $dpelettre;
    protected $gesval;
    protected $geslettre;

    public function __construct($type, $data)
    {
        if($type == 0){
            $this->dpeval               = $data[175];
            $this->dpelettre            = $data[176];
            $this->gesval               = $data[177];
            $this->geslettre            = $data[178];
        }elseif($type == 1){
            $this->dpeval               = (int) $data->DPE_VAL1;
            $this->dpelettre            = $data->DPE_ETIQ1;
            $this->gesval               = (int) $data->DPE_VAL2;
            $this->geslettre            = $data->DPE_ETIQ2;

            $this->setGeslettre($this->setToNullIfEmpty($this->geslettre));
            $this->setDpelettre($this->setToNullIfEmpty($this->dpelettre));

            if($this->dpelettre == null && $this->getDpelettre() == 0){
                $this->setDpeval(null);
            }

            if($this->geslettre == null && $this->getGesval() == 0){
                $this->setGesval(null);
            }
        }else{
            $dpe=null;$ges=null;$ldpe=null;$lges=null;
            foreach($data['regulations'] as $regulation){
                if($regulation['type'] == '1'){
                    $dpe = intval($regulation['value']);
                    if($dpe <= 50){
                        $ldpe = "A";
                    }elseif($dpe >= 51 && $dpe <= 90){
                        $ldpe = "B";
                    }elseif($dpe >= 91 && $dpe <= 150){
                        $ldpe = "C";
                    }elseif($dpe >= 151 && $dpe <= 230){
                        $ldpe = "D";
                    }elseif($dpe >= 231 && $dpe <= 330){
                        $ldpe = "E";
                    }elseif($dpe >= 331 && $dpe <= 450){
                        $ldpe = "F";
                    }elseif($dpe >= 451){
                        $ldpe = "G";
                    }
                }
                
                if($regulation['type'] == '2'){
                    $ges = intval($regulation['value']);
                    if($ges <= 5){
                        $lges = "A";
                    }elseif($ges >= 6 && $ges <= 10){
                        $lges = "B";
                    }elseif($ges >= 11 && $ges <= 20){
                        $lges = "C";
                    }elseif($ges >= 21 && $ges <= 35){
                        $lges = "D";
                    }elseif($ges >= 36 && $ges <= 55){
                        $lges = "E";
                    }elseif($ges >= 56 && $ges <= 80){
                        $lges = "F";
                    }elseif($ges >= 81){
                        $lges = "G";
                    }
                }
            }
            $this->dpeval               = $dpe;
            $this->dpelettre            = $ldpe;
            $this->gesval               = $ges;
            $this->geslettre            = $lges;
        }

        
        if($this->dpelettre == "V"){
            $this->setDpelettre('VI');
        }
        if($this->dpelettre == "Y" or $this->dpelettre == "X" or $this->dpelettre == "Z"){
            $this->setDpelettre('NS');
        }

        if($this->geslettre == "V"){
            $this->setGeslettre('VI');
        }
        if($this->geslettre == "Y" or $this->geslettre == "X" or $this->geslettre == "Z"){
            $this->setGeslettre('NS');
        }
    }

    /**
     * @return int
     */
    public function getDpeval()
    {
        return $this->dpeval;
    }

    /**
     * @param int $dpeval
     */
    public function setDpeval($dpeval): void
    {
        $this->dpeval = $dpeval;
    }

    /**
     * @return mixed
     */
    public function getDpelettre()
    {
        return $this->dpelettre;
    }

    /**
     * @param mixed $dpelettre
     */
    public function setDpelettre($dpelettre): void
    {
        $this->dpelettre = $dpelettre;
    }

    /**
     * @return int
     */
    public function getGesval()
    {
        return $this->gesval;
    }

    /**
     * @param int $gesval
     */
    public function setGesval($gesval): void
    {
        $this->gesval = $gesval;
    }

    /**
     * @return mixed
     */
    public function getGeslettre()
    {
        return $this->geslettre;
    }

    /**
     * @param mixed $geslettre
     */
    public function setGeslettre($geslettre): void
    {
        $this->geslettre = $geslettre;
    }

}

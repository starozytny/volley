<?php

namespace App\Manager\Import\Data;

use App\Manager\Import\DataSanitize;

class DataCopro extends DataSanitize implements Data
{
    protected $isCopro;
    protected $nbLot;
    protected $chargesAnnuelle;
    protected $hasProced;
    protected $detailsProced;

    public function __construct($type, $data)
    {
        if($type == 0){
            $this->isCopro                  = $data[257];
            $this->nbLot                    = $data[258];
            $this->chargesAnnuelle          = $data[259];
            $this->hasProced                = $data[260];
            $this->detailsProced            = $data[261];
        }elseif($type == 1){
            $this->isCopro                  = $data->COPROPRIETE;
            $this->nbLot                    = (int) $data->NB_LOTS_COPROPRIETE;
            $this->chargesAnnuelle          = (float) $data->MONTANT_QUOTE_PART;
            $this->hasProced                = $data->PROCEDURE_SYNDICAT;
            $this->detailsProced            = $data->DETAIL_PROCEDURE;
        }else{

            $nblot=0;$copro=null;$chargesAnnuelles=0;
            foreach($data['residence'] as $residence){
                $copro=true;
                $nblot += $residence['lots'];
                if($residence['period'] == '8'){
                    $chargesAnnuelles += $residence['fees'];
                }elseif($residence['period'] == '4'){
                    $chargesAnnuelles += $residence['fees']*12;
                }
            }

            $this->isCopro                  = $copro;
            $this->nbLot                    = $nblot;
            $this->chargesAnnuelle          = $chargesAnnuelles;
            $this->hasProced                = null;
            $this->detailsProced            = null;
        }

        $this->setIsCopro($this->convertToTrilean($this->isCopro));
        $this->setHasProced($this->convertToTrilean($this->hasProced));
    }

    /**
     * @return mixed
     */
    public function getisCopro()
    {
        return $this->isCopro;
    }

    /**
     * @param mixed $isCopro
     */
    public function setIsCopro($isCopro): void
    {
        $this->isCopro = $isCopro;
    }

    /**
     * @return mixed
     */
    public function getNbLot()
    {
        return $this->nbLot;
    }

    /**
     * @param mixed $nbLot
     */
    public function setNbLot($nbLot): void
    {
        $this->nbLot = $nbLot;
    }

    /**
     * @return mixed
     */
    public function getChargesAnnuelle()
    {
        return $this->chargesAnnuelle;
    }

    /**
     * @param mixed $chargesAnnuelle
     */
    public function setChargesAnnuelle($chargesAnnuelle): void
    {
        $this->chargesAnnuelle = $chargesAnnuelle;
    }

    /**
     * @return mixed
     */
    public function getHasProced()
    {
        return $this->hasProced;
    }

    /**
     * @param mixed $hasProced
     */
    public function setHasProced($hasProced): void
    {
        $this->hasProced = $hasProced;
    }

    /**
     * @return mixed
     */
    public function getDetailsProced()
    {
        return $this->detailsProced;
    }

    /**
     * @param mixed $detailsProced
     */
    public function setDetailsProced($detailsProced): void
    {
        $this->detailsProced = $detailsProced;
    }

}

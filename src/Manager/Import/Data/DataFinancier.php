<?php

namespace App\Manager\Import\Data;

use App\Manager\Import\DataSanitize;

class DataFinancier extends DataSanitize implements Data
{
    protected $prix;
    protected $honoraires;
    protected $charges;
    protected $foncier;
    protected $depotGarantie;
    protected $honoChargesDe;
    protected $horsHonoAcquereur;
    protected $modalitesChargesLocataire;
    protected $complementLoyer;
    protected $partHonoEdl;
    protected $bouquet;
    protected $rente;

    public function __construct($type, $data)
    {
        if($type == 0){
            $this->prix                             = $data[10];
            $this->honoraires                       = $data[14];
            $this->charges                          = $data[22];
            $this->foncier                          = $data[143];
            $this->depotGarantie                    = $data[160];
            $this->honoChargesDe                    = $data[301];
            $this->horsHonoAcquereur                = $data[302];
            $this->modalitesChargesLocataire        = $data[303];
            $this->complementLoyer                  = $data[304];
            $this->partHonoEdl                      = $data[305];
            $this->bouquet                          = $data[184];
            $this->rente                            = $data[185];

            $this->honoChargesDe = $this->setStringHonoChargesDe($this->honoChargesDe);
            $this->modalitesChargesLocataire = $this->setStringModalitesChargesLocataire($this->modalitesChargesLocataire);

        }elseif($type == 1){

            $tabSpecial = $this->setDataXml($data);

            $this->prix                             = $tabSpecial['PRIX'];
            $this->honoraires                       = (float) $data->HONORAIRES;
            $this->charges                          = (float) $data->MONTANT_CHARGES;
            $this->foncier                          = (float) $data->TAXE_FONCIERE;
            $this->depotGarantie                    = (float) $data->DEPOT_GARANTIE;
            $this->honoChargesDe                    = null;
            $this->horsHonoAcquereur                = (float) $data->HONO_ACQ;
            $this->modalitesChargesLocataire        = (string) $data->NATURE_CHARGES;
            $this->complementLoyer                  = (float) $data->COMPLEMENT_LOYER;
            $this->partHonoEdl                      = (float) $data->HONO_ETAT_LIEUX_TTC;

            $this->bouquet                          = null;
            $this->rente                            = null;

        }else{

            $taxeFonciere=null;
            foreach($data['services'] as $service){
                switch($service){
                    case '22':
                        $taxeFonciere = $service['value'];
                        break;
                }
            }

            $this->prix                             = (float) $data['price']['value'] + (float) $data['price']['fees'];
            $this->honoraires                       = (float) $data['price']['commission'];
            $this->charges                          = (float) $data['price']['fees'];
            $this->foncier                          = (float) $taxeFonciere;
            $this->depotGarantie                    = (float) $data['price']['deposit'];
            $this->honoChargesDe                    = null;
            $this->horsHonoAcquereur                = null;
            $this->modalitesChargesLocataire        = null;
            $this->complementLoyer                  = null;
            $this->partHonoEdl                      = (float) $data['price']['inventory'];
            $this->bouquet                          = $data['category'] == '5' ? (float) $data['price']['contribution'] : null;
            $this->rente                            = $data['price']['pension'] ? $data['price']['pension'] : null;
        }
    }

    /**
     * @return null
     */
    public function getBouquet()
    {
        return $this->bouquet;
    }

    /**
     * @param null $bouquet
     */
    public function setBouquet($bouquet): void
    {
        $this->bouquet = $bouquet;
    }

    /**
     * @return null
     */
    public function getRente()
    {
        return $this->rente;
    }

    /**
     * @param null $rente
     */
    public function setRente($rente): void
    {
        $this->rente = $rente;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix): void
    {
        $this->prix = $prix;
    }

    /**
     * @return mixed
     */
    public function getHonoraires()
    {
        return $this->honoraires;
    }

    /**
     * @param mixed $honoraires
     */
    public function setHonoraires($honoraires): void
    {
        $this->honoraires = $honoraires;
    }

    /**
     * @return mixed
     */
    public function getCharges()
    {
        return $this->charges;
    }

    /**
     * @param mixed $charges
     */
    public function setCharges($charges): void
    {
        $this->charges = $charges;
    }

    /**
     * @return mixed
     */
    public function getFoncier()
    {
        return $this->foncier;
    }

    /**
     * @param mixed $foncier
     */
    public function setFoncier($foncier): void
    {
        $this->foncier = $foncier;
    }

    /**
     * @return mixed
     */
    public function getDepotGarantie()
    {
        return $this->depotGarantie;
    }

    /**
     * @param mixed $depotGarantie
     */
    public function setDepotGarantie($depotGarantie): void
    {
        $this->depotGarantie = $depotGarantie;
    }

    /**
     * @return mixed
     */
    public function getHonoChargesDe()
    {
        return $this->honoChargesDe;
    }

    /**
     * @param mixed $honoChargesDe
     */
    public function setHonoChargesDe($honoChargesDe): void
    {
        $this->honoChargesDe = $honoChargesDe;
    }

    /**
     * @return mixed
     */
    public function getHorsHonoAcquereur()
    {
        return $this->horsHonoAcquereur;
    }

    /**
     * @param $horsHonoAcquereur
     */
    public function setHorsHonoAcquereur($horsHonoAcquereur): void
    {
        $this->horsHonoAcquereur = $horsHonoAcquereur;
    }

    /**
     * @return mixed
     */
    public function getModalitesChargesLocataire()
    {
        return $this->modalitesChargesLocataire;
    }

    /**
     * @param mixed $modalitesChargesLocataire
     */
    public function setModalitesChargesLocataire($modalitesChargesLocataire): void
    {
        $this->modalitesChargesLocataire = $modalitesChargesLocataire;
    }

    /**
     * @return mixed
     */
    public function getComplementLoyer()
    {
        return $this->complementLoyer;
    }

    /**
     * @param mixed $complementLoyer
     */
    public function setComplementLoyer($complementLoyer): void
    {
        $this->complementLoyer = $complementLoyer;
    }

    /**
     * @return mixed
     */
    public function getPartHonoEdl()
    {
        return $this->partHonoEdl;
    }

    /**
     * @param mixed $partHonoEdl
     */
    public function setPartHonoEdl($partHonoEdl): void
    {
        $this->partHonoEdl = $partHonoEdl;
    }

}

<?php

namespace App\Manager\Import\Data;

use App\Manager\Import\DataSanitize;

class DataBien extends DataSanitize implements Data
{
    protected $ref;
    protected $real_ref;
    protected $typeAnnonce;
    protected $typeBien;
    protected $typeT;
    protected $libelle;
    protected $descriptif;
    protected $dispo;
    protected $isPublished;
    protected $codeTypeAnnonce;
    protected $codeTypeBien;
    protected $isCopro;

    public function __construct($type, $data)
    {
        if($type == 0){
            $ref = $data[1] == null ? uniqid() : $data[1];
            // Bien
            $this->ref          = $ref;
            $this->real_ref     = $ref;
            $this->typeAnnonce  = $data[2];
            $this->typeBien     = $data[3];
            $this->typeT        = $data[17];
            $this->descriptif   = $data[20];
            $this->dispo        = $data[21];
            $this->isCopro      = $data[257];

            $this->setTypeAnnonce($this->capitalize($this->typeAnnonce));
            $this->setTypeBien($this->capitalize($this->typeBien));

        }elseif($type == 1){

            $tabSpecial = $this->setDataXml($data);

            $this->ref          = $data->NO_MANDAT;
            $this->real_ref     = $data->NO_MANDAT;
            $this->typeAnnonce  = $tabSpecial['TYPE_ANNONCE'];
            $this->typeBien     = $tabSpecial['TYPE_BIEN'];
            $this->typeT        = $data->NB_PIECES;
            $this->descriptif   = $data->TEXTE_FR;
            $this->dispo        = $data->DATE_DISPO;
            $this->isCopro      = $data->COPROPRIETE;
        }else{
            $ref = $data['reference'] == null ? uniqid() : $data['reference'];
            // Bien
            $this->ref          = trim($ref);
            $this->real_ref     = trim($ref);
            $this->typeAnnonce  = $data['category'];
            $this->typeBien     = $data['type'];
            $this->typeT        = $data['rooms'];
            $this->descriptif   = $data['comments'];
            $this->dispo        = $data['available_at'];
            $this->isCopro      = 3;

            $this->setTypeAnnonce($this->apimoAnnonce($this->typeAnnonce));
            $this->setTypeBien($this->apimoType($this->typeBien));
            $this->setDescriptif($this->apimoDescriptif($this->descriptif));
        }

        $this->setIsCopro($this->convertToTrilean($this->isCopro));

        if($this->typeT != '' && $this->typeBien == 'Appartement'){
            $this->setTypeT("T" . $this->typeT);
        }else{
            $this->setTypeT(null);
        }
        $this->setLibelle($this->typeBien . " " . $this->typeT);
        $this->setDispo($this->setToNullIfEmpty($this->dispo));
        if($this->getDispo() != null){
            if($type == 2){
                $newDate = \DateTime::createFromFormat('Y-m-d', $this->getDispo());
            }else{
                $newDate = \DateTime::createFromFormat('d/m/Y', $this->getDispo());
            }
            
            $this->setDispo($newDate);
        }

        $this->isPublished = true;
        $this->codeTypeAnnonce = $this->setCodeAnnonce($this->typeAnnonce);
        $this->codeTypeBien = $this->setCodeBien($this->typeBien);

    }

    /**
     * @return mixed
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @param mixed $ref
     */
    public function setRef($ref): void
    {
        $this->ref = $ref;
    }

    /**
     * @return mixed
     */
    public function getRealRef()
    {
        return $this->real_ref;
    }

    /**
     * @param mixed $real_ref
     */
    public function setRealRef($real_ref): void
    {
        $this->real_ref = $real_ref;
    }



    /**
     * @return mixed
     */
    public function getTypeAnnonce()
    {
        return $this->typeAnnonce;
    }

    /**
     * @param mixed $typeAnnonce
     */
    public function setTypeAnnonce($typeAnnonce): void
    {
        $this->typeAnnonce = $typeAnnonce;
    }

    /**
     * @return mixed
     */
    public function getTypeBien()
    {
        return $this->typeBien;
    }

    /**
     * @param mixed $typeBien
     */
    public function setTypeBien($typeBien): void
    {
        $this->typeBien = $typeBien;
    }

    /**
     * @return mixed
     */
    public function getTypeT()
    {
        return $this->typeT;
    }

    /**
     * @param mixed $typeT
     */
    public function setTypeT($typeT): void
    {
        $this->typeT = $typeT;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return mixed
     */
    public function getDescriptif()
    {
        return $this->descriptif;
    }

    /**
     * @param mixed $descriptif
     */
    public function setDescriptif($descriptif): void
    {
        $this->descriptif = $descriptif;
    }

    /**
     * @return mixed
     */
    public function getDispo()
    {
        return $this->dispo;
    }

    /**
     * @param mixed $dispo
     */
    public function setDispo($dispo): void
    {
        $this->dispo = $dispo;
    }

    /**
     * @return mixed
     */
    public function getisPublished()
    {
        return $this->isPublished;
    }

    /**
     * @param mixed $isPublished
     */
    public function setIsPublished($isPublished): void
    {
        $this->isPublished = $isPublished;
    }

    /**
     * @return int
     */
    public function getCodeTypeAnnonce()
    {
        return $this->codeTypeAnnonce;
    }

    /**
     * @param int $codeTypeAnnonce
     */
    public function setCodeTypeAnnonce($codeTypeAnnonce): void
    {
        $this->codeTypeAnnonce = $codeTypeAnnonce;
    }

    /**
     * @return mixed
     */
    public function getCodeTypeBien()
    {
        return $this->codeTypeBien;
    }

    /**
     * @param mixed $codeTypeBien
     */
    public function setCodeTypeBien($codeTypeBien): void
    {
        $this->codeTypeBien = $codeTypeBien;
    }

    /**
     * @return mixed
     */
    public function getIsCopro()
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

}

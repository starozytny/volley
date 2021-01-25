<?php

namespace App\Manager\Import\Data;

use App\Manager\Import\DataSanitize;

class DataCaracteristique extends DataSanitize implements Data
{

    // Caractéristique
    protected $surface;
    protected $surfaceTerrain;
    protected $surfaceSejour;
    protected $nbrPiece;
    protected $nbrChambre;
    protected $nbrSdb;
    protected $nbrSle;
    protected $nbrWc;
    protected $isWcSepare;
    protected $nbrBalcon;
    protected $nbrEtage;
    protected $etage;
    protected $isMeuble;
    protected $anneeConstruction;
    protected $isRefaitNeuf;
    protected $chauffage;
    protected $cuisine;
    protected $isSud;
    protected $isEst;
    protected $isOuest;
    protected $isNord;

    public function __construct($type, $data)
    {
        if($type == 0){
            $this->surface              = $data[15];
            $this->surfaceTerrain       = $data[16];
            $this->surfaceSejour        = $data[215];
            $this->nbrPiece             = $data[17];
            $this->nbrChambre           = $data[18];
            $this->nbrSdb               = $data[28];
            $this->nbrSle               = $data[29];
            $this->nbrWc                = $data[30];
            $this->isWcSepare           = $data[31];
            $this->nbrBalcon            = $data[38];
            $this->nbrEtage             = $data[24];
            $this->etage                = $data[23];
            $this->isMeuble             = $data[25];
            $this->anneeConstruction    = $data[26];
            $this->isRefaitNeuf         = $data[27];
            $this->chauffage            = $data[32];
            $this->cuisine              = $data[33];
            $this->isSud                = $data[34];
            $this->isEst                = $data[35];
            $this->isOuest              = $data[36];
            $this->isNord               = $data[37];

            $this->setIsWcSepare($this->convertToTrilean($this->isWcSepare));
            $this->setIsRefaitNeuf($this->convertToTrilean($this->isRefaitNeuf));
            $this->setIsMeuble($this->convertToTrilean($this->isMeuble));
            $this->setIsSud($this->convertToTrilean($this->isSud));
            $this->setIsNord($this->convertToTrilean($this->isNord));
            $this->setIsEst($this->convertToTrilean($this->isEst));
            $this->setIsOuest($this->convertToTrilean($this->isOuest));

            $this->setChauffage($this->setStringChauffage($this->chauffage));
            $this->setCuisine($this->setStringCuisine($this->cuisine));

        }elseif($type == 1){
            $this->surface              = (float) $data->SURF_HAB;
            $this->surfaceTerrain       = (float) $data->SURF_TERRAIN;
            $this->surfaceSejour        = (float) $data->SURF_SEJOUR;
            $this->nbrPiece             = (int) $data->NB_PIECES;
            $this->nbrChambre           = (int) $data->NB_CHAMBRES;
            $this->nbrSdb               = (int) $data->NB_SDB;
            $this->nbrSle               = (int) $data->NB_SE;
            $this->nbrWc                = (int) $data->NB_WC;
            $this->isWcSepare           = 3;
            $this->nbrBalcon            = (int) $data->BALCON;
            $this->nbrEtage             = (int) $data->NB_ETAGES;
            $this->etage                = (int) $data->ETAGE;
            $this->isMeuble             = 3;
            $this->anneeConstruction    = (int) $data->ANNEE_CONS;
            $this->isRefaitNeuf         = 3;
            $this->chauffage            = $data->TYPE_CHAUFF . " " . $data->NATURE_CHAUFF;
            $this->cuisine              = $data->CUISINE;
            $this->isSud                = 3;
            $this->isEst                = 3;
            $this->isOuest              = 3;
            $this->isNord               = 3;

            $this->setChauffage($this->capitalize($this->chauffage));
            $this->setCuisine($this->capitalize($this->cuisine));
        }else{
            $properties = $data['areas'];

            $terrain=null;$sejour=null;$sdb=null;$sde=null;$wc=null;$balcon=null;$cuisine = "";
            $wcSepare = 3;
            foreach($properties as $item){
                $number = intval($item['number']);
                $area = intval($item['area']);

                switch($item['type']){
                    case '51':
                        $terrain = $area;
                        break;
                    case '20':
                        $sejour = $area;
                        break;
                    case '8':
                        $sdb = $number;
                        break;
                    case '13':
                        $sde = $number;
                        break;
                    case '16':
                        $wc = $number;
                        break;
                    case '41':
                    case '42':
                        $wcSepare = false;
                        break;
                    case '43':
                        $balcon = $number;
                        break;
                    case '3':
                        foreach($item['comments'] as $comments){
                            $cuisine .= ' ' . $comments['comment'];                 
                        }
                        $cuisine = utf8_decode(ucfirst(trim(strtolower($cuisine))));
                        break;
                }
                if($cuisine == ""){$cuisine = null;}
            }

            $chauffage='';
            $chauffageDevice = array(
                '', 'Convecteur', 'Au sol', 'Radiateur', 'Poêle', 'Climatisation', 'Central', 'Sans chauffage'
            );
            $chauffageAccess = array(
                '', 'Individuel', 'Collectif', 'Mixte', 'Urbain'
            );
            $chauffageType = array(
                '', 'Gaz', 'Fioul / Mazout', 'électrique', 'Bois', 'Solaire', 'Charbon', 'Pompe à chaleur', 'Géothermie', 'Granulé de bois', 'Eau chaude', 'Aérothermie'
            );
            $cDevice = !$data['heating']['device'] ? 0 : $data['heating']['device'];
            $cAccess = !$data['heating']['access'] ? 0 : $data['heating']['access'];
            $cType = !$data['heating']['type'] ? 0 : $data['heating']['type'];
            $chauffage = $chauffageDevice[$cDevice] . ' ' . strtolower($chauffageAccess[$cAccess]) . ' ' . strtolower($chauffageType[$cType]);
            $chauffage = utf8_decode(ucfirst(trim($chauffage)));
            if($chauffage == ""){$chauffage = null;}

            $est=3;$nord=3;$ouest=3;$sud=3;
            foreach($data['orientations'] as $ori){
                switch($ori){
                    case 1:
                        $est = 1;
                        break;
                    case 2:
                        $nord = 1;
                        break;
                    case 3:
                        $ouest = 1;
                        break;
                    case 4:
                        $sud = 1;
                        break;
                }
            }

            $this->surface              = $data['area']['value'];
            $this->surfaceTerrain       = $terrain;
            $this->surfaceSejour        = $sejour;
            $this->nbrPiece             = $data['rooms'];
            $this->nbrChambre           = $data['bedrooms'];
            $this->nbrSdb               = $sdb;
            $this->nbrSle               = $sde;
            $this->nbrWc                = $wc;
            $this->isWcSepare           = $wcSepare;
            $this->nbrBalcon            = $balcon;
            $this->nbrEtage             = $data['floor']['levels'];
            $this->etage                = $data['floor']['value'];
            $this->isMeuble             = $data['subcategory'];
            $this->anneeConstruction    = $data['construction']['construction_year'];
            $this->isRefaitNeuf         = $data['condition'] ? $data['condition'] : 3;
            $this->chauffage            = $chauffage;
            $this->cuisine              = $cuisine;
            $this->isSud                = $sud;
            $this->isEst                = $est;
            $this->isOuest              = $ouest;
            $this->isNord               = $nord;

            if($this->isMeuble == "21"){
                $this->setIsMeuble(0);
            }elseif($this->isMeuble == "22"){
                $this->setIsMeuble(1);
            }else{
                $this->setIsMeuble(3);
            }
        }


    }

    /**
     * @return float
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * @param float $surface
     */
    public function setSurface($surface): void
    {
        $this->surface = $surface;
    }

    /**
     * @return float
     */
    public function getSurfaceTerrain()
    {
        return $this->surfaceTerrain;
    }

    /**
     * @param float $surfaceTerrain
     */
    public function setSurfaceTerrain($surfaceTerrain): void
    {
        $this->surfaceTerrain = $surfaceTerrain;
    }

    /**
     * @return float
     */
    public function getSurfaceSejour()
    {
        return $this->surfaceSejour;
    }

    /**
     * @param float $surfaceSejour
     */
    public function setSurfaceSejour($surfaceSejour): void
    {
        $this->surfaceSejour = $surfaceSejour;
    }

    /**
     * @return int
     */
    public function getNbrPiece()
    {
        return $this->nbrPiece;
    }

    /**
     * @param int $nbrPiece
     */
    public function setNbrPiece($nbrPiece): void
    {
        $this->nbrPiece = $nbrPiece;
    }

    /**
     * @return int
     */
    public function getNbrChambre()
    {
        return $this->nbrChambre;
    }

    /**
     * @param int $nbrChambre
     */
    public function setNbrChambre($nbrChambre): void
    {
        $this->nbrChambre = $nbrChambre;
    }

    /**
     * @return int
     */
    public function getNbrSdb()
    {
        return $this->nbrSdb;
    }

    /**
     * @param int $nbrSdb
     */
    public function setNbrSdb($nbrSdb): void
    {
        $this->nbrSdb = $nbrSdb;
    }

    /**
     * @return int
     */
    public function getNbrSle()
    {
        return $this->nbrSle;
    }

    /**
     * @param int $nbrSle
     */
    public function setNbrSle($nbrSle): void
    {
        $this->nbrSle = $nbrSle;
    }

    /**
     * @return int
     */
    public function getNbrWc()
    {
        return $this->nbrWc;
    }

    /**
     * @param int $nbrWc
     */
    public function setNbrWc($nbrWc): void
    {
        $this->nbrWc = $nbrWc;
    }

    /**
     * @return int
     */
    public function getisWcSepare()
    {
        return $this->isWcSepare;
    }

    /**
     * @param int $isWcSepare
     */
    public function setIsWcSepare($isWcSepare): void
    {
        $this->isWcSepare = $isWcSepare;
    }

    /**
     * @return int
     */
    public function getNbrBalcon()
    {
        return $this->nbrBalcon;
    }

    /**
     * @param int $nbrBalcon
     */
    public function setNbrBalcon($nbrBalcon): void
    {
        $this->nbrBalcon = $nbrBalcon;
    }

    /**
     * @return int
     */
    public function getNbrEtage()
    {
        return $this->nbrEtage;
    }

    /**
     * @param int $nbrEtage
     */
    public function setNbrEtage($nbrEtage): void
    {
        $this->nbrEtage = $nbrEtage;
    }

    /**
     * @return int
     */
    public function getEtage()
    {
        return $this->etage;
    }

    /**
     * @param int $etage
     */
    public function setEtage($etage): void
    {
        $this->etage = $etage;
    }

    /**
     * @return int
     */
    public function getisMeuble()
    {
        return $this->isMeuble;
    }

    /**
     * @param int $isMeuble
     */
    public function setIsMeuble($isMeuble): void
    {
        $this->isMeuble = $isMeuble;
    }

    /**
     * @return int
     */
    public function getAnneeConstruction()
    {
        return $this->anneeConstruction;
    }

    /**
     * @param int $anneeConstruction
     */
    public function setAnneeConstruction($anneeConstruction): void
    {
        $this->anneeConstruction = $anneeConstruction;
    }

    /**
     * @return int
     */
    public function getisRefaitNeuf()
    {
        return $this->isRefaitNeuf;
    }

    /**
     * @param int $isRefaitNeuf
     */
    public function setIsRefaitNeuf($isRefaitNeuf): void
    {
        $this->isRefaitNeuf = $isRefaitNeuf;
    }

    /**
     * @return string
     */
    public function getChauffage()
    {
        return $this->chauffage;
    }

    /**
     * @param string $chauffage
     */
    public function setChauffage($chauffage): void
    {
        $this->chauffage = $chauffage;
    }

    /**
     * @return mixed
     */
    public function getCuisine()
    {
        return $this->cuisine;
    }

    /**
     * @param mixed $cuisine
     */
    public function setCuisine($cuisine): void
    {
        $this->cuisine = $cuisine;
    }

    /**
     * @return int
     */
    public function getisSud()
    {
        return $this->isSud;
    }

    /**
     * @param int $isSud
     */
    public function setIsSud($isSud): void
    {
        $this->isSud = $isSud;
    }

    /**
     * @return int
     */
    public function getisEst()
    {
        return $this->isEst;
    }

    /**
     * @param int $isEst
     */
    public function setIsEst($isEst): void
    {
        $this->isEst = $isEst;
    }

    /**
     * @return int
     */
    public function getisOuest()
    {
        return $this->isOuest;
    }

    /**
     * @param int $isOuest
     */
    public function setIsOuest($isOuest): void
    {
        $this->isOuest = $isOuest;
    }

    /**
     * @return int
     */
    public function getisNord()
    {
        return $this->isNord;
    }

    /**
     * @param int $isNord
     */
    public function setIsNord($isNord): void
    {
        $this->isNord = $isNord;
    }

}

<?php

namespace App\Manager\Import;


use App\Entity\Immo\ImBien;
use Doctrine\ORM\EntityManagerInterface;

abstract class DataSanitize
{
    /**
     * return true si il existe deja une agence ayant le nom passé en parametre
     * @param EntityManagerInterface $em
     * @param $class
     * @param $identifier
     * @param $id
     * @return bool
     */
    protected function isExiste(EntityManagerInterface $em, $class, $identifier, $id){

        $data = $em->getRepository($class)->findOneBy(array($identifier => $id));
        if(!$data){
            return false;
        }else{
            return true;
        }
    }
    /**
     * Clean "!" last char
     * @param $data
     * @return array
     */
    protected function cleaner($data){
        $final = [];
        foreach ($data as $datum) {
            $item = substr($datum,0, (strlen($datum) - 1));
            $item = $this->reformatCara($item);
            $item = $this->setToNullIfEmpty($item);
            array_push($final, $item);
        }
        return $final;
    }
    /**
     * Reformate carac
     * @param $data
     * @return mixed
     */
    protected function reformatCara($data){

        $spe = array('é','ê','è','à','ô','<','>');
        $noSpe = array('é','ê','è','à','ô','<','>');

        $data = utf8_encode($data);

        return str_replace($spe, $noSpe, $data);
    }

    /**
     * set $data to null if empty ""
     * @param $data
     * @return string|null
     */
    protected function setToNullIfEmpty($data){
        $data = trim($data);
        if($data == '' || strlen($data) == 0){
            return null;
        }else{
            return $data;
        }
    }

    /**
     * Set majuscule for first carac
     * @param $data
     * @return string
     */
    protected function capitalize($data){
        $data = strtolower($data);
        return ucfirst($data);
    }

    /**
     * Convert number to string honoraire charges de
     * @param $item
     * @return string|null
     */
    protected function setStringHonoChargesDe($item)
    {
        switch ($item){
            case 1:
                return "Acquéreur";
                break;
            case 2:
                return "Vendeur";
                break;
            case 3:
                return "Acquéreur et vendeur";
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * Convert number to string modalite charges locataires
     * @param $item
     * @return string|null
     */
    protected function setStringModalitesChargesLocataire($item)
    {
        switch ($item){
            case 1:
                return "Forfaitaires mensuelles";
                break;
            case 2:
                return "Prévisionnelles mensuelles avec régularisation annuelle";
                break;
            case 3:
                return "Remboursement annuel par le locataire";
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * Convert boolean to good int
     * false = 0
     * true = 1
     * inconnu = 3
     * @param $data
     * @return bool|int
     */
    protected function convertToTrilean($data){
        if( $data == "NON" || $data == "Non" || $data == "0" || $data == 0){
            return false;
        }else if( $data == "N.C" || $data == null || $data == "" || $data == 3 || $data == "3"){
            return 3;
        }else{
            return true;
        }

    }

    /**
     * Formate le tel a 10 car
     * @param $data
     * @return mixed|string|string[]|null
     */
    protected function formattedTel($data){
        $data = trim($data);
        $data = str_replace(" ", "", $data);
        if(!is_numeric($data)){
            $data = preg_replace('/[^0-9]/', '', $data);
        }
        $a = substr($data, 0,2);
        $b = substr($data, 2,2);
        $c = substr($data, 4,2);
        $d = substr($data, 6,2);
        $e = substr($data, 8,2);

        $data = $a . $b . $c . $d . $e;
        $data = trim($data);
        if($data == ""){
            return null;
        }else{
            return $data;
        }
    }

    /**
     * Traduit les code postaux de Marseille en arrondissement
     * @param $data
     * @param $ville
     * @return string
     */
    protected function postalCodeConvert($data, $ville){
        if($ville == 'Marseille'){
            $ardt = substr($data, 3, strlen($data));
            $first = substr($ardt,0,1);
            if($first == 0){
                $ardt = substr($ardt,1,1);
            }

            return $ardt;
        }
    }

    /**
     * Suprimer les caractères numeriques
     * @param $data
     * @return mixed|string
     */
    protected function deleteNumeric($data){
        $data = str_replace([0,1,2,3,4,5,6,7,8,9], '', $data);
        $data = trim($data);
        return $data;
    }

    /**
     * Traduit les code chiffré de seLoger en string cuisine
     * @param $item
     * @return string|null
     */
    protected function setStringCuisine($item)
    {
        switch ($item) {
            case'1':
                return 'Aucune';
                break;
            case'2':
                return 'Américaine';
                break;
            case'3':
                return 'Séparée';
                break;
            case'4':
                return 'Industrielle';
                break;
            case'5':
                return 'Coin cuisine';
                break;
            case'6':
                return 'Américaine équipée';
                break;
            case'7':
                return 'Séparée équipée';
                break;
            case'8':
                return 'Coin cuisine équipé';
                break;
            case'9':
                return 'Equipée';
                break;
            default:
                return null;
                break;
        }
    }

    /**
     * Traduit les code chiffré de seLoger en string chauffage
     * @param $item
     * @return string|null
     */
    protected function setStringChauffage($item)
    {
        switch ($item){
            case'128':
                return 'Radiateur';
                break;
            case'256':
                return 'Sol';
                break;
            case'384':
                return 'Mixte';
                break;

            // --------------------------
            case'512':
                return 'Gaz';
                break;
            case'640':
                return 'Gaz radiateur';
                break;
            case'768':
                return 'Gaz sol';
                break;
            case'896':
                return 'Gaz mixte';
                break;

            // --------------------------
            case'1024':
                return 'Fuel';
                break;
            case'1152':
                return 'Fuel radiateur';
                break;
            case'1280':
                return 'Fuel sol';
                break;
            case'1408':
                return 'Fuel mixte';
                break;

            // --------------------------
            case'2048':
                return 'Electrique';
                break;
            case'2176':
                return 'Electrique radiateur';
                break;
            case'2304':
                return 'Electrique sol';
                break;
            case'2432':
                return 'Electrique mixte';
                break;

            // --------------------------
            case'4096':
                return 'Collectif';
                break;
            case'4224':
                return 'Collectif radiateur';
                break;
            case'4352':
                return 'Collectif au sol';
                break;
            case'4480':
                return 'Collectif mixte';
                break;
            case'4608':
                return 'Collectif gaz';
                break;
            case'4736':
                return 'Collectif gaz radiateur';
                break;
            case'4864':
                return 'Collectif gaz sol';
                break;
            case'4992':
                return 'Collectif gaz mixte';
                break;
            case'5120':
                return 'Collectif fuel';
                break;
            case'5248':
                return 'Collectif fuel radiateur';
                break;
            case'5376':
                return 'Collectif fuel sol';
                break;
            case'5504':
                return 'Collectif fuel mixte';
                break;
            case'6144':
                return 'Collectif électrique';
                break;
            case'6272':
                return 'Collectif électrique radiateur';
                break;
            case'6400':
                return 'Collectif électrique sol';
                break;
            case'6528':
                return 'Collectif électrique mixte';
                break;

            // --------------------------
            case'8192':
                return 'Individuel';
                break;
            case'8320':
                return 'Individuel radiateur';
                break;
            case'8448':
                return 'Individuel sol';
                break;
            case'8576':
                return 'Individuel mixte';
                break;
            case'8704':
                return 'Individuel gaz';
                break;
            case'8832':
                return 'Individuel gaz radiateur';
                break;
            case'8960':
                return 'Individuel gaz sol';
                break;
            case'9088':
                return 'Individuel gaz mixte';
                break;
            case'9216':
                return 'Individuel fuel';
                break;
            case'9344':
                return 'Individuel fuel radiateur';
                break;
            case'9472':
                return 'Individuel fuel sol';
                break;
            case'9600':
                return 'Individuel fuel mixte';
                break;
            case'10240':
                return 'Individuel électrique';
                break;
            case'10368':
                return 'Individuel électrique radiateur';
                break;
            case'10496':
                return 'Individuel électrique sol';
                break;
            case'10624':
                return 'Individuel électrique mixte';
                break;

            //-------------------
            case'16384':
                return 'Climatisation réversible';
                break;
            case'20480':
                return 'Climatisation réversible centrale';
                break;
            case'24576':
                return 'Climatisation réversible individuelle';
                break;

            default:
                return null;
                break;
        }
    }

    /**
     * Cleaner XML
     * @param $dataXML
     * @return array
     */
    protected function setDataXml($dataXML){
        switch($dataXML->TYPE_OFFRE){
            case 1:
                $annonce = 'Vente';
                $bien = 'Appartement';
                break;
            case 2:
                $annonce = 'Vente';
                $bien = 'Maison';
                break;
            case 3 :
                $annonce = 'Vente';
                $bien = 'Terrain';
                break;
            case 4 :
                $annonce = 'Location';
                $bien = 'Immeuble';
                break;
            case 5 :
                $annonce = 'Vente';
                $bien = 'Local';
                break;
            case 6 :
                $annonce = 'Vente';
                $bien = 'Fond de commerce';
                break;
            case 7 :
                $annonce = 'Vente';
                $bien = 'Parking';
                break;
            case 11:
                $annonce = 'Location';
                $bien = 'Appartement';
                break;
            case 12:
                $annonce = 'Location';
                $bien = 'Maison';
                break;
            case 13 :
                $annonce = 'Location';
                $bien = 'Local';
                break;
            case 14 :
                $annonce = 'Location';
                $bien = 'Parking';
                break;
            default:
                $annonce = 'Inconnu';
                $bien = 'Inconnu';
                break;
        }
        $prix = 0;
        if($annonce == 'Vente'){
            $prix = (float) $dataXML->PRIX;
        }else{
            $prix = (float) $dataXML->LOYER;
        }
        $tabSpecial = [
            'TYPE_ANNONCE' => $annonce,
            'TYPE_BIEN' => $bien,
            'PRIX' => $prix
        ];
        return $tabSpecial;
    }

    public function specialCaracCuisineXML($item){
        switch ($item){
            case 'Amenagee':
                return 'Aménagée';
                break;
            case 'Equipee':
                return 'Equipée';
                break;
            case 'Separee':
                return 'Séparée';
                break;
            case 'Americaine':
                return 'Américaine';
                break;
            default:
                return $item;
                break;
        }
    }

    public function specialCaracChauffageXML($item){
        return str_replace('electricite', 'électrique', $item);
    }

    public function apimoAnnonce($item)
    {
        $tab = array('', 'Vente', 'Location', 'Location saisonnière', 'Programme', 'Viager', 'Enchère');
        return $tab[$item];
    }

    public function apimoType($item)
    {
        $tab = array('', 'Appartement', 'Maison', '	Terrain', '	Commerce', 'Parking', 'Immeuble', 'Bureau', 'Bateau', 'Local', 'Parking');
        return $tab[$item];
    }

    public function apimoDescriptif($item)
    {
        $descriptif = "";
        foreach($item as $i){
            $descriptif .= $i['comment'];   
        }
        return trim($descriptif);
    }

    protected function setCodeAnnonce($item)
    {
        switch ($item) {
            case 'Location':
                return ImBien::NATURE_LOCATION;
                break;
            default:
                return ImBien::NATURE_VENTE;
                break;
        }
    }
    protected function setCodeBien($item)
    {
        switch ($item) {
            case 'Maison':
            case 'Maison/villa':
                return ImBien::TYPE_MAISON;
                break;
            case 'Appartement':
                return ImBien::TYPE_APPARTEMENT;
                break;
            case 'Parking':
            case 'Parking/box':
            case 'Box':
                return ImBien::TYPE_PARKING;
                break;
            case 'Bureaux':
                return ImBien::TYPE_BUREAUX;
                break;
            case 'Local':
                return ImBien::TYPE_LOCAL;
                break;
            case 'Immeuble':
                return ImBien::TYPE_IMMEUBLE;
                break;
            case 'Terrain':
                return ImBien::TYPE_TERRAIN;
                break;
            case 'Commerce':
            case 'Fond de commerce':
                return ImBien::TYPE_FOND_COMMERCE;
                break;
            default:
                return 9;
                break;
        }
    }
}

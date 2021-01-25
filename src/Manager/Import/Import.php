<?php


namespace  App\Manager\Import;


use App\Entity\Immo\ImAdresse;
use App\Entity\Immo\ImAgence;
use App\Entity\Immo\ImBien;
use App\Entity\Immo\ImCaracteristique;
use App\Entity\Immo\ImCommodite;
use App\Entity\Immo\ImCopro;
use App\Entity\Immo\ImDiagnostic;
use App\Entity\Immo\ImFinancier;
use App\Entity\Immo\ImImage;
use App\Entity\Immo\ImResponsable;
use App\Manager\Import\Data\DataAdresse;
use App\Manager\Import\Data\DataAgence;
use App\Manager\Import\Data\DataBien;
use App\Manager\Import\Data\DataCaracteristique;
use App\Manager\Import\Data\DataCommodite;
use App\Manager\Import\Data\DataCopro;
use App\Manager\Import\Data\DataDiagnostic;
use App\Manager\Import\Data\DataFinancier;
use App\Manager\Import\Data\DataImage;
use App\Manager\Import\Data\DataResponsable;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class Import extends DataSanitize
{
    protected $type;
    protected $record;
    protected $exportDirectory;
    protected $em;

    public function __construct($privateDirectory, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->exportDirectory = $privateDirectory;
    }
    public function import($type, $folder, $record, $tabPathImg)
    {
        $this->type = $type;
        $this->record = ($type == 0) ? $this->cleaner($record) : $record;

        $bien = $this->createBien($folder);
        if($bien != false){
            $this->createImage($folder, $tabPathImg, $bien);
        }
        $this->em->flush();     
    }

    /**
     * @param $folder
     * @return bool|ImBien
     */
    protected function createBien($folder){
        $agence = $this->createAgence($folder);
        $financier = $this->createFinancier();
        $copro = $this->createCopro();
        $diagnostic = $this->createDiagnostic();
        $responsable = $this->createResponsable();
        $commodite = $this->createCommodite();
        $carac = $this->createCaracteristique($commodite);
        $adresse = $this->createAdresse();

        $data = new DataBien($this->type, $this->record);

        $ref = $this->setUniqueRef($data->getRef(), $agence->getId());

        if(!$this->isExiste($this->em, ImBien::class, 'ref', $data->getRef())){

            $reader = new Csv();
            $oldId = null;
            if(file_exists($this->getExportDirectory() . '/export/save_biens.csv')){
                dump("in");
                try {
                    $spreadsheet = $reader->load($this->getExportDirectory() . '/export/save_biens.csv');
                    $sheetData = $spreadsheet->getActiveSheet()->toArray();
                    foreach ($sheetData as $item) {

                        if($item[1] == $data->getRealRef() &&
                            $item[2] == $data->getCodeTypeAnnonce() &&
                            $item[3] == $data->getCodeTypeBien() &&
                            $item[4] == $agence->getDirname()
                        ){
                            $oldId = $item[0];
                        }
                    }
                } catch (Exception $e) {
                    var_dump('Error load bien.csv : ' . $e);
                } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                    var_dump('Error get active sheet : ' . $e);
                }
            }

            $bien = new ImBien();
            $bien->setRef($ref);
            $bien->setNature($data->getTypeAnnonce());
            $bien->setType($data->getTypeBien());
            $bien->setTypeT($data->getTypeT());
            $bien->setLibelle($data->getLibelle());
            $bien->setDescriptif($data->getDescriptif());
            $bien->setDateDispo($data->getDispo());
            $bien->setNatureCode($data->getCodeTypeAnnonce());
            $bien->setTypeCode($data->getCodeTypeBien());
            $bien->setAgence($agence);
            $bien->setFinancier($financier);
            $bien->setCopro($copro);
            $bien->setResponsable($responsable);
            $bien->setDiagnostic($diagnostic);
            $bien->setCaracteristique($carac);
            $bien->setAdresse($adresse);
            $bien->setRealRef($data->getRef());
            $bien->setIsCopro($data->getIsCopro());
            $bien->setIdentifiant(uniqid($agence->getId().$data->getCodeTypeAnnonce().$data->getCodeTypeBien()));

            if($oldId != null){
                $bien->setIdentifiant($oldId);
            }

            $this->em->persist($bien);
            return $bien;
        }else{
            var_dump("Le bien de cette référence existe déjà : " . $data->getRef());
            return false;
        }
    }

    protected function createAgence($folder)
    {
        $data = new DataAgence($this->type, $this->record, $folder);

        if(!$this->isExiste($this->em, ImAgence::class, 'dirname', $data->getDirname())){
            $agence = (new ImAgence())
                ->setName($data->getAgenceName())
                ->setDirname($data->getDirname())
            ;
            $this->em->persist($agence);
            return $agence;
        }else{
            return $this->em->getRepository(ImAgence::class)->findOneBy(array('dirname' => $data->getDirname()));
        }
    }

    protected function createFinancier(): ImFinancier
    {
        $data = new DataFinancier($this->type, $this->record);
        $financier = (new ImFinancier())
            ->setPrix($data->getPrix())
            ->setHonoraires($data->getHonoraires())
            ->setCharges($data->getCharges())
            ->setFoncier($data->getFoncier())
            ->setDepotGarantie($data->getDepotGarantie())
            ->setHonoChargesDe($data->getHonoChargesDe())
            ->setHorsHonoAcquereur($data->getHorsHonoAcquereur())
            ->setModalitesChargesLocataire($data->getModalitesChargesLocataire())
            ->setComplementLoyer($data->getComplementLoyer())
            ->setPartHonoEdl($data->getPartHonoEdl())
            ->setBouquet($data->getBouquet())
            ->setRente($data->getRente())
        ;
        $this->em->persist($financier);
        return $financier;
    }

    protected function createCopro(): ImCopro
    {
        $data = new DataCopro($this->type, $this->record);
        $copro = (new ImCopro())
//            ->setIsCopro($data->getisCopro())
            ->setNbLot($data->getNbLot())
            ->setChargesAnnuelle($data->getChargesAnnuelle())
            ->setHasProced($data->getHasProced())
            ->setDetailsProced($data->getDetailsProced())
        ;

        $this->em->persist($copro);
        return $copro;
    }

    protected function createDiagnostic(): ImDiagnostic
    {
        $data = new DataDiagnostic($this->type, $this->record);
        $diag = (new ImDiagnostic())
            ->setDpeval($data->getDpeval())
            ->setDpelettre($data->getDpelettre())
            ->setGesval($data->getGesval())
            ->setGeslettre($data->getGeslettre())
        ;

        $this->em->persist($diag);
        return $diag;
    }

    protected function setResponsable(DataResponsable $data): ImResponsable
    {
        $responsable = (new ImResponsable())
            ->setContact($data->getContact())
            ->setTel($data->getTel())
            ->setEmail($data->getEmail())
            ->setCodeNego($data->getCodeNego())
        ;

        $this->em->persist($responsable);
        return $responsable;
    }

    protected function createResponsable()
    {
        $data = new DataResponsable($this->type, $this->record);
        $contact = $data->getContact();
        if(!$this->isExiste($this->em, ImResponsable::class, 'contact', $contact)){
            return $this->setResponsable($data);
        }else{
            if(!$this->isExiste($this->em, ImResponsable::class, 'tel', $data->getTel())){
                return $this->setResponsable($data);
            }else{
                return $this->em->getRepository(ImResponsable::class)->findOneBy(array('contact' => $contact));
            }
        }
    }

    protected function createCommodite(): ImCommodite
    {
        $data = new DataCommodite($this->type, $this->record);
        $commodite = (new ImCommodite())
            ->setHasAscenseur($data->getHasAscenceur())
            ->setHasCave($data->getHasCave())
            ->setHasInterphone($data->getHasInterphone())
            ->setHasGardien($data->getHasGardien())
            ->setHasTerrasse($data->getHasTerrasse())
            ->setHasClim($data->getHasClim())
            ->setHasPiscine($data->getHasPiscine())
            ->setNbParking($data->getNbParking())
            ->setNbBox($data->getNbBox())
        ;

        $this->em->persist($commodite);
        return $commodite;
    }

    protected function createCaracteristique(ImCommodite $commodite): ImCaracteristique
    {
        $data = new DataCaracteristique($this->type, $this->record);
        $cuisine = $this->specialCaracCuisineXML($data->getCuisine());
        $chauffage = $this->specialCaracChauffageXML($data->getChauffage());
        $hasCommo = 1;
        if($commodite->getHasAscenseur() == 0 && $commodite->getHasCave() == 0 && $commodite->getHasInterphone() == 0
            && $commodite->getHasGardien() == 0 && $commodite->getHasTerrasse() == 0 && $commodite->getHasClim() == 0
            && $commodite->getHasPiscine() == 0 && $commodite->getNbParking() != 0 && $commodite->getNbBox() != 0){
            $hasCommo = 0;
        }

        $carac = new ImCaracteristique();
        $carac->setSurface($data->getSurface());
        $carac->setSurfaceTerrain($data->getSurfaceTerrain());
        $carac->setSurfaceSejour($data->getSurfaceSejour());
        $carac->setNbPiece($data->getNbrPiece());
        $carac->setNbChambre($data->getNbrChambre());
        $carac->setNbSdb($data->getNbrSdb());
        $carac->setNbSe($data->getNbrSle());
        $carac->setNbWc($data->getNbrWc());
        $carac->setIsWcSepare($data->getisWcSepare());
        $carac->setNbBalcon($data->getNbrBalcon());
        $carac->setNbEtage($data->getNbrEtage());
        $carac->setEtage($data->getEtage());
        $carac->setIsMeuble($data->getisMeuble());
        $carac->setAnneeConstruction($data->getAnneeConstruction());
        $carac->setIsRefaitneuf($data->getisRefaitNeuf());
        $carac->setTypeChauffage($chauffage);
        $carac->setTypeCuisine($cuisine);
        $carac->setIsSud($data->getisSud());
        $carac->setIsNord($data->getisNord());
        $carac->setIsEst($data->getisEst());
        $carac->setIsOuest($data->getisOuest());
        $carac->setHasCommodite($hasCommo);
        $carac->setCommodite($commodite);

        $this->em->persist($carac);

        return $carac;
    }

    protected function createAdresse(): ImAdresse
    {
        $data = new DataAdresse($this->type, $this->record);
        $adresse = (new ImAdresse())
            ->setAdr($data->getAdr())
            ->setVille($data->getVille())
            ->setCp($data->getCp())
            ->setArdt($data->getArdt())
            ->setQuartier($data->getQuartier())
            ->setLat($data->getLat())
            ->setLon($data->getLon())
        ;

        $this->em->persist($adresse);
        return $adresse;
    }

    private function setUniqueRef($ref, $agenceID): string
    {
        $ref = trim($ref);
        $ref = str_replace([" ", "-", "/", "_", "'", "\""], "", $ref);
        if(strlen($ref) > 10){
            $ref = uniqid();
        }
        if(ctype_alpha($ref)){
            $ref = uniqid();
        }
        return $agenceID . "|" . $ref;
    }

    protected function createImage($folder, $tabPathImg, ImBien $bien): array
    {
        $data = new DataImage($this->type, $this->record, $folder, $tabPathImg);
        $tab = $data->getImages();
        $images = array();
        for ($i=0 ; $i < count($tab) ; $i++){

            $file = $tab[$i]->getFile();
            $thumbs = $tab[$i]->getThumbs();

            if($file != null){
                $image = (new ImImage())
                    ->setFile($file)
                    ->setRang($i)
                    ->setOrientation(false)
                ;
                if($i == 0){
                    $image->setThumb($thumbs);
                }
                $bien->setFirstImage($tab[0]->getFile());
                $this->em->persist($image);
                $bien->addImage($image);
                $this->em->persist($bien);
                array_push($images, $image);
            }

        }

        return $images;
    }

    public function getExportDirectory()
    {
        return $this->exportDirectory;
    }
}

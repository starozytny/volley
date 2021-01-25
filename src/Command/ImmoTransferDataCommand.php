<?php

namespace App\Command;

use App\Manager\Image\ImageManager;
use App\Manager\Import\Import;
use App\Service\DatabaseService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImmoTransferDataCommand extends Command
{
    protected static $defaultName = 'immo:transfer:data';

    const ANNONCE_CSV = 0;
    const ANNONCE_XML = 1;
    const ANNONCE_JSON = 2;

    private $filenameData = 'annonces.csv';
    private $filenameDataMaj = 'Annonces.csv';

    private $folderData;
    private $folderDepot;
    private $folderExtracted;
    private $folderArchived;

    private $folderAnnonces;
    private $folderImages;
    private $folderThumbs;

    private $databaseService;
    private $imageManager;
    private $params;
    private $import;
    private $io;

    public function __construct(DatabaseService $databaseService, ParameterBagInterface $params, ImageManager $imageManager, Import $import)
    {
        parent::__construct();

        $this->databaseService = $databaseService;
        $this->imageManager = $imageManager;
        $this->params = $params;
        $this->import = $import;

        $folderPublic = $this->params->get('kernel.project_dir') . '/public/';
        $this->folderData = $folderPublic .'data/';
        $this->folderDepot = $folderPublic .'data/depot/';
        $this->folderExtracted = $folderPublic .'data/extracted/';
        $this->folderArchived = $folderPublic .'data/archived/';

        $this->folderAnnonces = $folderPublic .'annonces/';
        $this->folderImages = $folderPublic .'annonces/images/';
        $this->folderThumbs = $folderPublic .'annonces/thumbs/';
    }

    protected function configure()
    {
        $this
            ->setDescription('Extract ZIP archive and transfer data in file Annonce.csv')
            ->addArgument('firstCall', InputArgument::REQUIRED, '1 si premier appel 0 sinon')
            ->addArgument('apiImmo', InputArgument::OPTIONAL, 'si renseigné oui');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        // --------------  INITIALISATION DES DOSSIERS  -----------------------
        $this->io->title('Initialisation des dossiers');
        $this->initFolders();

        // --------------  VARIABLES  -----------------------
        $firstCall = $input->getArgument('firstCall') == 1;
        $haveApiImmo = $input->getArgument('apiImmo') ? true : false;

        // --------------  RECHERCHE DES ZIP  -----------------------
        $this->io->title('Recherche et décompression des zips');
        $archives = scandir($this->folderDepot);
        $folders = $this->extractZIP($archives); // exit auto if return false

        if($folders !== false){
            // -------------- SI CEST LE PREMIER APPEL  -----------------------
            if($firstCall){
                // --------------  SAVE ID DATA ALREADY EXISTED -----------------------
                // //---------------- TODO

                // --------------  RESET DES TABLES  -----------------------
                $this->io->title('Reset des tables');
                $this->databaseService->resetTable($this->io, ['im_agency']);
            }

            // --------------  VARIABLES PROCESS FOLDER  -----------------------
            $archives = $this->getOriginalArchives($archives);
            $folder = $folders[0]; // get first folder
            $archive = $archives[0]; // get first archive

            // -------------- REINITIALISATION DES DOSSIERS IMG
            $this->io->title('Suppression des anciennes images');
            $this->deleteFolder($this->folderImages . $folder);
            $this->deleteFolder($this->folderThumbs . $folder);
            $this->io->text('Suppression des images [OK]');

            $this->io->title('Transfert des images');
            $this->imageManager->moveImages($this->io, $this->folderExtracted, $this->folderImages, $this->folderThumbs, $folder);

            // --------------  TRANSFERT DES DATA  -----------------------
            $this->io->title('Traitement du dossier');
            $this->transferData($folder, $output);
        }

        $this->io->newLine();
        $this->io->comment('--- [FIN DE LA COMMANDE] ---');
        return Command::SUCCESS;
    }

    /**
     * Create folders if do not exist
     */
    protected function initFolders()
    {
        $folders = array(
            $this->folderData, $this->folderDepot, $this->folderExtracted, $this->folderArchived,
            $this->folderAnnonces, $this->folderImages, $this->folderThumbs
        );

        foreach ($folders as $directory) {
            if(!is_dir($directory)){
                mkdir($directory);
            }
        }

        $this->io->text('Initialisation des dossiers [OK]');
    }

    /**
     * Get name of archive without ext
     * @param $item
     * @return string|string[]
     */
    protected function getDirname($item){
        $nameFolder = strtolower(substr($item,0, (strlen($item)-4)));
        $nameFolder = str_replace(" ", "_", $nameFolder);
        return $nameFolder;
    }

    /**
     * Fonction permettant de décompresser les zip dans le dossier extract
     * @param $archives
     * @return array|false
     */
    protected function extractZIP($archives){
        $isEmpty = true;
        $isOpen = false;
        $folders = array();
        foreach ($archives as $item) {
            $archive = new \ZipArchive();
            if(preg_match('/([^\s]+(\.(?i)(zip))$)/i', $item, $matches)){
                $isEmpty = false;
                if($archive->open($this->folderDepot . $item) == true){
                    $nameFolder = $this->getDirname($item);
                    if($isOpen == false){
                        $archive->extractTo($this->folderExtracted . $nameFolder);
                        $archive->close(); unset($archive);
                        $this->io->text("Archive " . $nameFolder . " [OK]");

                        array_push($folders, $nameFolder);
                        $isOpen = true;
                    }
                }else{
                    $this->io->error("Erreur archive");
                    return false;
                }
            }
        }
        if($isEmpty){
            $this->io->comment("Aucun zip dans le dossier dépot.");
            return false;
        }
        return $folders;
    }

    /**
     * Get original Archives
     * @param $archives
     * @return array
     */
    protected function getOriginalArchives($archives): array
    {
        $folders = array();
        foreach ($archives as $item) {
            if(preg_match('/([^\s]+(\.(?i)(zip))$)/i', $item, $matches)){
                array_push($folders, $item);
            }
        }
        return $folders;
    }

    /**
     * Delete folder
     * @param $folder
     */
    protected function deleteFolder($folder){
        if(is_dir($folder)){
            $clean = scandir($folder);

            foreach ($clean as $entry) {
                if ($entry != "." && $entry != "..") { unlink($folder . '/' . $entry); }
            }
            rmdir($folder);
        }
    }

    /**
     * Transfert des datas d'un folder
     * @param $folder
     * @param $output
     */
    protected function transferData($folder, OutputInterface $output)
    {
        $filename = $this->folderExtracted . $folder . '/' . $this->filenameData;
        $filenameMaj = $this->folderExtracted . $folder . '/' . $this->filenameDataMaj;

        if (file_exists($filename) || file_exists($filenameMaj)) {

            $nameFile = file_exists($filename) ? $filename : $filenameMaj;

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            $reader->setDelimiter('#');

            $spreadsheet = $reader->load($nameFile);
            $data = $spreadsheet->getActiveSheet()->toArray();

            $this->process(self::ANNONCE_CSV, $output, $data, $folder);

        } else { // XML --- PERICLES
//            $files = scandir($this->PATH_EXTRACT . $folder);
//            $isFind = false;
//            foreach ($files as $file) {
//                if (preg_match('/([^\s]+(\.(?i)(xml))$)/i', $file, $matches)) {
//                    $annonces = $file;
//                    $isFind = true;
//                }
//            }
//
//            if ($isFind) {
//                $parseFile = simplexml_load_file($this->PATH_EXTRACT . $folder . "/" . $annonces, 'SimpleXMLElement', LIBXML_NOCDATA);
//                $count = count($parseFile); // Nombre de records
//
//                $this->traitement(self::ANNONCE_XML, $io, $output, $folder, $count, $parseFile, $tabPathImg);
//
//            } else {
//                $io->error('Aucun fichier annonce trouvé dans le dossier : ' . $folder);
//            }
        }
    }

    /**
     * Lance le traitement de du transfert de data
     * @param $type
     * @param $output
     * @param $data
     * @param $folder
     */
    protected function process($type, $output, $data, $folder)
    {
        $tabPathImg = [
            'images' => $this->folderImages,
            'thumbs' => $this->folderThumbs
        ];
        $count = count($data);

        if ($count != 0) {
            $progressBar = new ProgressBar($output, $count);
            $progressBar->setFormat("%current%/%max% [%bar%] %percent:3s%%  🏁");
            $progressBar->setOverwrite(true);
            $progressBar->start();

            // Insertion des datas csv dans la DBB
            foreach ($data as $record) {
                $this->import->import($type, $folder, $record, $tabPathImg);
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->io->text('------- Completed !');
            $reader = null;unset($reader);unset($records);
        } else {
            $this->io->warning("Aucune ligne contenu dans le fichier.");
        }
        $this->io->newLine(1);
    }
}

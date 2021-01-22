<?php

namespace App\Command;

use App\Service\DatabaseService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImmoTransferDataCommand extends Command
{
    protected static $defaultName = 'immo:transfer:data';

    private string $folderData;
    private string $folderDepot;
    private string $folderExtracted;
    private string $folderArchived;

    private DatabaseService $databaseService;
    private ParameterBagInterface $params;
    private SymfonyStyle $io;

    public function __construct(DatabaseService $databaseService, ParameterBagInterface $params)
    {
        parent::__construct();

        $this->databaseService = $databaseService;
        $this->params = $params;

        $folderPublic = $this->params->get('kernel.project_dir') . '/public/';
        $this->folderData = $folderPublic .'data/';
        $this->folderDepot = $folderPublic .'data/depot/';
        $this->folderExtracted = $folderPublic .'data/extracted/';
        $this->folderArchived = $folderPublic .'data/archived/';
    }

    protected function configure()
    {
        $this
            ->setDescription('Extract ZIP archive and transfer data in file Annonce.csv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        // --------------  RECHERCHE DES ZIP  -----------------------
        $this->io->title('Recherche et décompression des zips');
        $archives = scandir($this->folderDepot);
        $folders = $this->extractZIP($archives); // exit auto if return false

        if($folders !== false){
            // --------------  INITIALISATION DES DOSSIERS  -----------------------
            $this->io->title('Initialisation des dossiers');
            $this->initFolders();

            // --------------  RESET DES TABLES  -----------------------
            $this->io->title('Reset des tables');
            $this->databaseService->resetTable($this->io, ['im_agency']);
        }

        $this->io->newLine();
        $this->io->comment('--- [FIN DE LA COMMANDE] ---');
        return Command::SUCCESS;
    }

    protected function initFolders()
    {
        $folders = array(
            $this->folderData, $this->folderDepot, $this->folderExtracted, $this->folderArchived
        );

        foreach ($folders as $directory) {
            if(!is_dir($directory)){
                mkdir($directory);
            }
        }

        $this->io->text('Initialisation des dossiers [OK]');
    }

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
}

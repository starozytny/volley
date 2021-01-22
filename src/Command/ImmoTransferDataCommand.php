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

        // --------------  INITIALISATION DES DOSSIERS  -----------------------
        $this->io->title('Initialisation des dossiers');
        $this->initFolders();

        $this->io->title('Reset des tables');
        $this->databaseService->resetTable($this->io, ['im_agency']);

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
}

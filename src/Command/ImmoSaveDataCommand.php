<?php

namespace App\Command;

use App\Entity\Immo\ImBien;
use App\Service\Export;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImmoSaveDataCommand extends Command
{
    protected static $defaultName = 'immo:save:data';

    protected $em;
    protected $export;
    protected $params;

    public function __construct(EntityManagerInterface $em, Export $export, ParameterBagInterface $params)
    {
        parent::__construct();

        $this->em = $em;
        $this->export = $export;
        $this->params = $params;
    }

    protected function configure()
    {
        $this
            ->setDescription('import old data in csv to compare which is alive')
        ;
    }

    protected function exportOldData()
    {
        $biens = $this->em->getRepository(ImBien::class)->findAll();
        $data = array();

        foreach ($biens as $bien) {
            $tmp = array(
                $bien->getIdentifiant(),
                $bien->getRealRef(),
                $bien->getNatureCode(),
                $bien->getTypeCode(),
                $bien->getAgence()->getDirname()
            );

            array_push($data, $tmp);
        }

        $header = array(array(
            'identifiant', 'ref', 'annonce', 'bien', 'dirname'
        ));

        return $this->export->createFile('csv', 'Liste des biens anciens', 'save_biens.csv', $header, $data, 5, 'export/');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->exportOldData();

        $io->newLine();
        $io->comment('--- [FIN DE LA COMMANDE] ---');
        return Command::SUCCESS;
    }
}

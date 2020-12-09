<?php


namespace App\Service;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DatabaseService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function resetTable(SymfonyStyle $io, $list)
    {
        foreach ($list as $item) {
            $connection = $this->entityManager->getConnection();

            $connection->beginTransaction();
            try {
                $connection->query('SET FOREIGN_KEY_CHECKS=0');
                $connection->executeUpdate(
                    $connection->getDatabasePlatform()->getTruncateTableSQL(
                        $item, true
                    )
                );
                $connection->query('SET FOREIGN_KEY_CHECKS=1');
                $connection->commit();
            } catch (DBALException $e) {
                $io->error('Database reset [FAIL] : ' . $e);
            }

        }
        $io->text('Reset [OK]');

    }
}
<?php

namespace App\Command;

use App\Service\OrphanageManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UploaderClearOrphansCommand extends Command
{
    protected static $defaultName = 'app:uploader:clear-orphans';

    private $manager;

    public function __construct(OrphanageManager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Effacez les téléchargements orphelins en fonction de l\'âge maximum que vous avez défini dans votre configuration.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->progressStart();

        $this->manager->clear();

        $io->progressFinish();
        $io->success('Les téléchargements d\'images orphelines ont été effacés');

        return 0;
    }
}


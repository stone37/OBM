<?php

namespace App\Command;

use App\Entity\Advert;
use App\Model\IndexerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexCommand extends Command
{
    protected static $defaultName = 'app:search:index';
    private $indexer;
    private $normalizer;
    private $em;

    public function __construct(
        IndexerInterface $indexer,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer
    ) {
        parent::__construct();
        $this->indexer = $indexer;
        $this->em = $em;
        $this->normalizer = $normalizer;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        $io->progressStart();
        $this->indexer->clean();

        $types = [Advert::class];

        foreach ($types as $type) {
            $items = $this->em->getRepository(Advert::class)->findBy([
                'validated' => true, 'denied' => false, 'deleted' => false]);
            foreach ($items as $item) {
                $io->progressAdvance();
                $this->indexer->index((array) $this->normalizer->normalize($item, 'search'));
            }
            $this->em->clear();
        }

        $io->progressFinish();
        $io->success('Les contenus ont bien été indexés');

        return 0;
    }
}

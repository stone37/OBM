<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Report>
 */
class ReportRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }
}

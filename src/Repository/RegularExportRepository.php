<?php

namespace App\Repository;

use App\Entity\RegularExport;
use App\Util\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RegularExport|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegularExport|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegularExport[]    findAll()
 * @method RegularExport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegularExportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegularExport::class);
    }

    public function findAllPaginated(int $limit, int $page): Paginator
    {
        $qb = $this->createQueryBuilder('re');

        return (new Paginator($qb, $limit))->paginate($page);
    }
}

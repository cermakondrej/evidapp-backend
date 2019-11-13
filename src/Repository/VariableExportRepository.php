<?php

namespace App\Repository;

use App\Entity\VariableExport;
use App\Util\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VariableExport|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariableExport|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariableExport[]    findAll()
 * @method VariableExport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariableExportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VariableExport::class);
    }

    public function findAllPaginated(int $limit, int $page): Paginator
    {
        $qb = $this->createQueryBuilder('ve');

        return (new Paginator($qb, $limit))->paginate($page);
    }
}

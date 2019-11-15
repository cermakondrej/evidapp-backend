<?php

namespace App\Repository;

use App\Entity\VariableWorkExport;
use App\Util\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VariableWorkExport|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariableWorkExport|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariableWorkExport[]    findAll()
 * @method VariableWorkExport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariableExportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VariableWorkExport::class);
    }

    public function findAllPaginated(int $limit, int $page): Paginator
    {
        $qb = $this->createQueryBuilder('ve');

        return (new Paginator($qb, $limit))->paginate($page);
    }
}

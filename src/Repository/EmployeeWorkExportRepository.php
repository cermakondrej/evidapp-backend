<?php

namespace App\Repository;

use App\Entity\EmployeeWorkExport;
use App\Util\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method EmployeeWorkExport|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeWorkExport|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeWorkExport[]    findAll()
 * @method EmployeeWorkExport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeWorkExportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeWorkExport::class);
    }

    public function findAllPaginated(int $limit, int $page): Paginator
    {
        $qb = $this->createQueryBuilder('re');

        return (new Paginator($qb, $limit))->paginate($page);
    }
}

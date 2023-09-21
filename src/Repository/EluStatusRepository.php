<?php

namespace App\Repository;

use App\Entity\EluStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EluStatus>
 *
 * @method EluStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method EluStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method EluStatus[]    findAll()
 * @method EluStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EluStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EluStatus::class);
    }

//    /**
//     * @return EluStatus[] Returns an array of EluStatus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EluStatus
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

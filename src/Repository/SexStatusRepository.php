<?php

namespace App\Repository;

use App\Entity\SexStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SexStatus>
 *
 * @method SexStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method SexStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method SexStatus[]    findAll()
 * @method SexStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SexStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SexStatus::class);
    }

//    /**
//     * @return SexStatus[] Returns an array of SexStatus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SexStatus
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

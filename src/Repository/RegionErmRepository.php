<?php

namespace App\Repository;

use App\Entity\RegionErm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegionErm>
 *
 * @method RegionErm|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegionErm|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegionErm[]    findAll()
 * @method RegionErm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionErmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegionErm::class);
    }

//    /**
//     * @return RegionErm[] Returns an array of RegionErm objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RegionErm
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

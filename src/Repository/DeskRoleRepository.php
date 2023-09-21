<?php

namespace App\Repository;

use App\Entity\DeskRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeskRole>
 *
 * @method DeskRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeskRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeskRole[]    findAll()
 * @method DeskRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeskRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeskRole::class);
    }

//    /**
//     * @return DeskRole[] Returns an array of DeskRole objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DeskRole
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

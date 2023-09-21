<?php

namespace App\Repository;

use App\Entity\Elu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Elu>
 *
 * @method Elu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Elu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Elu[]    findAll()
 * @method Elu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EluRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Elu::class);
    }

//    /**
//     * @return Elu[] Returns an array of Elu objects
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

//    public function findOneBySomeField($value): ?Elu
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

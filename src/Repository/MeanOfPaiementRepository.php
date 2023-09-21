<?php

namespace App\Repository;

use App\Entity\MeanOfPaiement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeanOfPaiement>
 *
 * @method MeanOfPaiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeanOfPaiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeanOfPaiement[]    findAll()
 * @method MeanOfPaiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeanOfPaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeanOfPaiement::class);
    }

//    /**
//     * @return MeanOfPaiement[] Returns an array of MeanOfPaiement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MeanOfPaiement
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\MeetingName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeetingName>
 *
 * @method MeetingName|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeetingName|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeetingName[]    findAll()
 * @method MeetingName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetingNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeetingName::class);
    }

//    /**
//     * @return MeetingName[] Returns an array of MeetingName objects
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

//    public function findOneBySomeField($value): ?MeetingName
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\CategoryOfReunion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryOfReunion>
 *
 * @method CategoryOfReunion|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryOfReunion|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryOfReunion[]    findAll()
 * @method CategoryOfReunion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryOfReunionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryOfReunion::class);
    }

    public function save(CategoryOfReunion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CategoryOfReunion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CategoryOfReunion[] Returns an array of CategoryOfReunion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategoryOfReunion
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

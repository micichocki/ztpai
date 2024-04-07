<?php

namespace App\Repository;

use App\Entity\TypeOfCuisine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeOfCuisine>
 *
 * @method TypeOfCuisine|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOfCuisine|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOfCuisine[]    findAll()
 * @method TypeOfCuisine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOfCuisineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeOfCuisine::class);
    }

    //    /**
    //     * @return TypeOfCuisine[] Returns an array of TypeOfCuisine objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TypeOfCuisine
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

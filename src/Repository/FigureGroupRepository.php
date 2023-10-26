<?php

namespace App\Repository;

use App\Entity\FigureGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FigureGroup>
 *
 * @method FigureGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method FigureGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method FigureGroup[]    findAll()
 * @method FigureGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FigureGroup::class);
    }

//    /**
//     * @return FigureGroup[] Returns an array of FigureGroup objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FigureGroup
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

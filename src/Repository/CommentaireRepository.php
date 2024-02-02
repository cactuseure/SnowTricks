<?php

namespace App\Repository;

use App\Entity\Commentaire;
use App\Entity\Figure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaire>
 *
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    public function findPaginatedComments(int $trickId, int $limit, int $offset): array
    {
        $qb = $this->createQueryBuilder('c');

        return $qb
            ->andWhere('c.figure = :trickId')
            ->setParameter('trickId', $trickId)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTotalComments(int $trickId): int
    {
        $qb = $this->createQueryBuilder('c');

        return (int) $qb
            ->select('COUNT(DISTINCT c.id)')
            ->andWhere('c.figure = :trickId')
            ->setParameter('trickId', $trickId)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getSingleScalarResult();
    }
}

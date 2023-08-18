<?php

namespace App\Repository;

use App\Entity\ViewHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ViewHistory>
 *
 * @method ViewHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ViewHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ViewHistory[]    findAll()
 * @method ViewHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ViewHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewHistory::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ViewHistory $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ViewHistory $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    public function findOneByUserAndProduct(object $user, object $product): ?ViewHistory
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.user = :user')
            ->andWhere('v.product = :product')
            ->setParameter('user', $user)
            ->setParameter('product', $product)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findUserViewHistory(object $user): array
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.user = :user')
            ->setParameter('user', $user)
            ->orderBy('v.viewedAt', 'DESC')
            ->setMaxResults(20)
            ->getQuery()
            ->getResult();
    }
    public function countByUser(object $user): int
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->andWhere('vh.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Comment $entity, bool $flush = true): void
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
    public function remove(Comment $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    public function findAllWithSearchQuery(?string $search): ?object
    {
        $qb = $this->createQueryBuilder('c');

        if ($search) {
            $qb
                ->andWhere('c.content LIKE :search OR u.firstName LIKE :search OR p.product LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        return $qb
            ->innerJoin('c.user', 'u')
            ->addSelect('u')
            ->innerJoin('c.product', 'p')
            ->addSelect('p')
            ->orderBy('c.createdAt', 'DESC')
            ;
    }
    public function findLastComments()
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.product', 'p')
            ->addSelect('p')
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}

<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Category $entity, bool $flush = true): void
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
    public function remove(Category $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return Category[] Returns an array of Category objects
      */

    public function findCategoryActive(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.isActive = true')
            ->orderBy('c.sortIndex', 'ASC')
            ->getQuery()
            ->getResult();

    }
    public function findFavoriteCategories(int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.favorites = true')
            ->orderBy('c.sortIndex', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findAllWithSearchQuery(?string $search): object
    {
        $qb = $this->createQueryBuilder('c');

        if ($search) {
            $qb
                ->andWhere('c.name LIKE :search OR c.isActive :search OR c.favorites :search')
                ->setParameter('search', "%$search%")
            ;
        }

        return $qb->orderBy('c.name', 'DESC');
    }
}

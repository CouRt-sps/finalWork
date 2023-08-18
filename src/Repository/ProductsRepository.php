<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Products $entity, bool $flush = true): void
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
    public function remove(Products $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByLimitedFields(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.limitedEdition = true')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByLimitedFields(): Products
    {
        $results = $this->createQueryBuilder('p')
            ->andWhere('p.limitedEdition = true')
            ->getQuery()
            ->getResult();

        shuffle($results);

        return array_shift($results);
    }

    public function findDiscountField(int $productId): ?int
    {
        $result = $this->createQueryBuilder('p')
            ->leftJoin('p.discounts', 'd')
            ->select('d.value')
            ->andWhere('p.id = :productId')
            ->setParameter('productId', $productId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $result !== null ? $result['value'] : null;
    }

    public function findDiscountFields(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.discounts', 'd')
            ->select('d.value')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTopProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.sortIndex', 'ASC')
            ->addOrderBy('p.quantityBuy', 'DESC')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMinPriceByCategory($category): float
    {
        return $this->createQueryBuilder('p')
            ->select('MIN(pr.price)')
            ->join('p.prices', 'pr')
            ->where('p.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findDiscountsFields(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.discounts IS NOT NULL')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByPopularity($direction = 'DESC'): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.quantityBuy', $direction)
            ->getQuery()
            ->getResult();
    }

    public function findProductsBySeller(int $sellerId): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.prices', 'price')
            ->join('price.seller', 'seller')
            ->where('seller.id = :sellerId')
            ->setParameter('sellerId', $sellerId)
            ->orderBy('p.quantityBuy', 'DESC')
            ->setMaxResults(11)
            ->getQuery()
            ->getResult();
    }
    public function findAllWithSearchQuery(?string $search): object
    {
        $qb = $this->createQueryBuilder('p');

        if ($search) {
            $qb
                ->andWhere('p.product LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        return $qb
            ->innerJoin('p.prices', 'pr')
            ->addSelect('pr')
            ->orderBy('p.createdAt', 'DESC')
        ;
    }
}

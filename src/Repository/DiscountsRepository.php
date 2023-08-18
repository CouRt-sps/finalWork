<?php

namespace App\Repository;

use App\Entity\Discounts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Discounts>
 *
 * @method Discounts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discounts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discounts[]    findAll()
 * @method Discounts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discounts::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Discounts $entity, bool $flush = true): void
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
    public function remove(Discounts $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findAllWithSearchQuery(?string $search): object
    {
        $qb = $this->createQueryBuilder('d');

        if ($search) {
            $qb
                ->andWhere('d.value LIKE :search OR d.description LIKE :search')
                ->setParameter('search', "%$search%")
            ;
        }

        return $qb->orderBy('d.id', 'DESC');
    }
}

<?php

namespace App\Service;

use App\Entity\Orders;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderHistoryService
{

    private OrdersRepository $ordersRepository;
    private EntityManagerInterface $em;

    public function __construct(OrdersRepository $ordersRepository, EntityManagerInterface $em)
    {
        $this->ordersRepository = $ordersRepository;
        $this->em = $em;
    }

    public function getAllOrderHistory(): array
    {
        return $this->ordersRepository->findAll();
    }

    public function editPaymentMethod($user): void
    {
        $this->ordersRepository->findOneBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );
        $entity = $this->em->getRepository(Orders::class)->find($user);
        $entity->setPaymentStatus('оплачен');
        $this->em->flush();
    }
}
<?php

namespace App\Service;

use App\Entity\Products;
use App\Entity\User;
use App\Entity\ViewHistory;
use App\Repository\ViewHistoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class ViewHistoryService
{
    private EntityManagerInterface $entityManager;
    private ViewHistoryRepository $viewHistoryRepository;

    public function __construct(ViewHistoryRepository $viewHistoryRepository, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->viewHistoryRepository = $viewHistoryRepository;
    }

    public function addToViewHistory(User $user, Products $product): void
    {
        $viewHistory = $this->viewHistoryRepository->findOneByUserAndProduct($user, $product);

        if (!$viewHistory) {
            $viewHistory = new ViewHistory();
            $viewHistory->setUser($user);
            $viewHistory->setProduct($product);
        }

        $viewHistory->setViewedAt(new \DateTime());

        $this->entityManager->persist($viewHistory);
        $this->entityManager->flush();
    }

    public function removeFromViewHistory(User $user, Products $product): void
    {
        $viewHistory = $this->viewHistoryRepository->findOneByUserAndProduct($user, $product);

        if ($viewHistory) {
            $this->entityManager->remove($viewHistory);
            $this->entityManager->flush();
        }
    }

    public function getViewedProducts(User $user): array
    {
        return $this->viewHistoryRepository->findUserViewHistory($user);
    }

    public function getViewedProductsCount(User $user): int
    {
        return $this->viewHistoryRepository->countByUser($user);
    }
}
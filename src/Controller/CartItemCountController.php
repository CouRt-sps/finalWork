<?php

namespace App\Controller;

use App\Entity\CartItem;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Не работает!!! не выполняется AJAX запрос
 */
class CartItemCountController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/cart/{id}/vote/up", name="vote_up", methods={"POST"})
     */
    public function like(CartItem $cartItem, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $logger->info('Кто-то лайкнул статью');
        $cartItem->like();
        $em->flush();

        return $this->json(['votes' => $cartItem->getQuantity()]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/cart/{id}/vote/down", name="vote_down", methods={"POST"})
     */
    public function dislike(CartItem $cartItem, EntityManagerInterface $em)
    {
        $cartItem->dislike();
        $em->flush();

        return $this->json(['votes' => $cartItem->getQuantity()]);
    }
}
{

}
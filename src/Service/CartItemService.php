<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\User;
use App\Repository\CartItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;


class CartItemService
{
    private CartItemRepository $cartItemRepository;
    private ProductsService $productsService;
    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(
        CartItemRepository $cartItemRepository,
        ProductsService $productsService,
        EntityManagerInterface $em,
        Security $security
    ) {
        $this->cartItemRepository = $cartItemRepository;
        $this->productsService = $productsService;
        $this->em = $em;
        $this->security = $security;
    }

    public function addCartItem(int $id): void
    {
        $product = $this->productsService->getProductById($id);
        if (!$cartItem = $this->cartItemRepository->findOneBy(['product' => $id])) {

            $user = $this->security->getUser();

            $cart = new Cart();
            $cart->setUser($user);

            $cartItem = new CartItem();
            $cartItem
                ->setProduct($product)
                ->setQuantity(1)
                ->setCart($cart);

            $this->em->persist($cart);

        } else {
            $cartItem->setQuantity($cartItem->getQuantity() + 1);
        }
        $this->em->persist($cartItem);
        $this->em->flush();
    }

    public function getCartItemsForUser(object $user): array
    {
        return $this->cartItemRepository->findCartItemsByUser($user);
    }

    public function getTotalPrice(array $cartItem): int
    {
        $total = 0;

        foreach ($cartItem as $instance) {
            $price = $instance->getProduct()->getPrices()->first();
            $priceValue = $price ? $price->getPrice() : null;
            $total += $priceValue * $instance->getQuantity();
        }

        return $total;

    }

    public function deletedCart(object $user): void
    {
        $cartItems = $this->cartItemRepository->findCartItemsByUser($user);

        foreach ($cartItems as $cartItem) {
            $this->cartItemRepository->remove($cartItem);
        }

        $this->em->flush();;
    }
}
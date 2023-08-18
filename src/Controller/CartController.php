<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Service\CartItemService;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private CategoryService $categoryService;
    private CartItemService $cartItemService;

    public function __construct(CategoryService $categoryService, CartItemService $cartItemService)
    {
        $this->categoryService = $categoryService;
        $this->cartItemService = $cartItemService;
    }

    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $categories = $this->categoryService->getCategory();
        $cartItems = $this->cartItemService->getCartItemsForUser($user);
        $total = $this->cartItemService->getTotalPrice($cartItems);
        $userId = $user->getId();

        return $this->render('cart/index.html.twig', [
            'categories' => $categories,
            'cartItems' => $cartItems,
            'total' => $total,
            'userId' => $userId,
        ]);
    }

    /**
     * @Route("/add-to-cart/{id}", name="app_cart_addtocart")
     */
    public function addToCart(int $id): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('app_login');
        }
        $this->cartItemService->addCartItem($id);
        return $this->redirectToRoute('app_catalog');
    }

    /**
     * @Route("/cart/{id}/vote/{type<up|down>}", methods={"POST"}, name="app_cart_like")
     */
    public function like(CartItem $cartItem, $type, EntityManagerInterface $em)
    {
        if ($type === 'up') {
            $cartItem->voteUp();
        } else {
            $cartItem->voteDown();
        }

        $em->flush();

        return $this->json(['votes' => $cartItem->getQuantity()]);
    }
}

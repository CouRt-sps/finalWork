<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Form\OrdersFormType;
use App\Service\CartItemService;
use App\Service\CategoryService;
use App\Service\OrderHistoryService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private CategoryService $categoryService;
    private UserService $userService;
    private CartItemService $cartItemService;
    private OrderHistoryService $orderHistoryService;

    public function __construct(
        CategoryService $categoryService,
        UserService $userService,
        CartItemService $cartItemService,
        OrderHistoryService $orderHistoryService
    ) {
        $this->categoryService = $categoryService;
        $this->userService = $userService;
        $this->cartItemService = $cartItemService;
        $this->orderHistoryService = $orderHistoryService;
    }

    /**
     * @Route("/order/{total}/{id}", name="app_order")
     */
    public function index($total, $id, Request $request, EntityManagerInterface $em): Response
    {
        $categories = $this->categoryService->getCategory();
        $user = $this->userService->getUserById($id);
        $cartItems = $this->cartItemService->getCartItemsForUser($user);

        $form = $this->createForm(OrdersFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Orders $orders */
            $orders = $form->getData();

            $orders
                ->setTotalPrice($total)
                ->setPaymentStatus('не оплачен')
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setUser($user)
            ;

            $em->persist($orders);
            $em->flush();

            $this->addFlash('flash_message', 'Заказ успешно сформирован');

            //return $this->redirectToRoute('app_order');
        }

        return $this->render('order/index.html.twig', [
            'categories' => $categories,
            'orderForm' => $form->createView(),
            'user' => $user->getFirstName(),
            'total' => $total,
            'cartItems' => $cartItems,
        ]);
    }

    /**
     * @Route("/order/payment/cart/{total}", name="app_order_paymentcart")
     */
    public function paymentCart(int $total): Response
    {
        $categories = $this->categoryService->getCategory();
        return $this->render('order/payment/cart.html.twig', [
            'categories' => $categories,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/order/payment/alien/{total}", name="app_order_paymentalien")
     */
    public function paymentAlien(int $total): Response
    {
        $categories = $this->categoryService->getCategory();
        return $this->render('order/payment/alien.html.twig', [
            'categories' => $categories,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/payment", name="app_order_paymentfinish")
     */
    public function paymentFinish(): Response
    {
        $categories = $this->categoryService->getCategory();

        $user = $this->getUser();
        $this->cartItemService->deletedCart($user);

        $this->orderHistoryService->editPaymentMethod($user);

        return $this->render('order/payment/finish.html.twig', [
            'categories' => $categories,
        ]);
    }
}

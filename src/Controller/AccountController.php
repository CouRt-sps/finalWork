<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountFormType;
use App\Service\CategoryService;
use App\Service\OrderHistoryService;
use App\Service\ViewHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_USER")
 * @method User|null getUser()
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class AccountController extends AbstractController
{
    private CategoryService $categoryService;
    private UserPasswordEncoderInterface $passwordEncoder;
    private ViewHistoryService $viewHistoryService;
    private OrderHistoryService $orderHistoryService;

    public function __construct(
        CategoryService $categoryService,
        UserPasswordEncoderInterface $passwordEncoder,
        ViewHistoryService $viewHistoryService,
        OrderHistoryService $orderHistoryService
    ) {
        $this->categoryService = $categoryService;
        $this->passwordEncoder = $passwordEncoder;
        $this->viewHistoryService = $viewHistoryService;
        $this->orderHistoryService = $orderHistoryService;
    }

    /**
     * @Route("/account", name="app_account")
     */
    public function index(): Response
    {
        $categories = $this->categoryService->getCategory();

        return $this->render('account/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/account/{id}/edit", name="app_account_edit_profile")
     */
    public function editProfile(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(AccountFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();
            $repeatPassword = $form->get('repeatPassword')->getData();

            if ((($password != null) && ($repeatPassword != null)) && ($password === $repeatPassword)) {
                $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
                $user->setPassword($encodedPassword);

            } elseif (($password != null) && ($repeatPassword != null)) {
                $this->addFlash('error', 'Пароли не совпадают.');
                return $this->redirectToRoute('app_account_edit_profile', ['id' => $user->getId()]);

            }

            $user->setImageFilename($form->get('imageFilename')->getData());
            $user->setEmail($form->get('email')->getData());
            $user->setFirstName($form->get('firstName')->getData());

            $em->persist($user);
            $em->flush();

            $this->addFlash('flash_message', 'Профиль успешно изменен');

            return $this->redirectToRoute('app_account_edit_profile', ['id' => $user->getId()]);
        }

        return $this->render('account/profile.html.twig', [
            'accountForm' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/account/viewhistory", name="app_account_view_history")
     */
    public function viewHistory(): Response
    {
        $categories = $this->categoryService->getCategory();
        /** @var User $user */
        $user = $this->getUser();

        $viewedProducts = $this->viewHistoryService->getViewedProducts($user);

        return $this->render('account/view.html.twig', [
            'categories' => $categories,
            'viewedProducts' => $viewedProducts,
        ]);
    }

    /**
     * @Route("/account/orderhistory", name="app_account_orderhistory")
     */
    public function orderHistory(): Response
    {
        $categories = $this->categoryService->getCategory();
        $orderHistory = $this->orderHistoryService->getAllOrderHistory();

        return $this->render('account/order.html.twig', [
            'categories' => $categories,
            'orderHistory' => $orderHistory,
        ]);
    }
}

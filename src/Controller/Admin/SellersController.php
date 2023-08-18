<?php

namespace App\Controller\Admin;

use App\Entity\Seller;
use App\Form\SellerFormType;
use App\Service\CategoryService;
use App\Service\SellerService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class SellersController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/admin/sellers", name="app_admin_sellers")
     */
    public function index(Request $request, PaginatorInterface $paginator, SellerService $sellerService): Response
    {
        $categories = $this->categoryService->getCategory();
        $perPage = $request->query->getInt('perPage', 20);

        $pagination = $paginator->paginate(
            $sellerService->getAllWithSearchQuery(
                $request->query->get('q')),
            $request->query->getInt('page', 1),
            $perPage
        );

        return $this->render('admin/sellers/index.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("admin/sellers/create", name="app_admin_sellers_create")
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(SellerFormType::class);

        if ($seller = $this->handleFormRequest($form, $em, $request)) {
            $this->addFlash('flash_message', 'Продавец успешно создан');

            return $this->redirectToRoute('app_admin_sellers');
        }

        return $this->render('admin/sellers/create.html.twig', [
            'sellerForm' => $form->createView(),
            'categories' => $categories,
            'showError' => $form->isSubmitted(),
        ]);
    }

    /**
     * @Route("admin/sellers/{id}/edit", name="app_admin_sellers_edit")
     */
    public function edit(Seller $seller, EntityManagerInterface $em, Request $request): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(SellerFormType::class, $seller);

        if ($seller = $this->handleFormRequest($form, $em, $request)) {
            $this->addFlash('flash_message', 'Продавец успешно изменен');

            return $this->redirectToRoute('app_admin_sellers_edit', ['id' => $seller->getId()]);
        }

        return $this->render('admin/sellers/edit.html.twig', [
            'sellerForm' => $form->createView(),
            'categories' => $categories,
            'showError' => $form->isSubmitted(),
        ]);
    }
    private function handleFormRequest(FormInterface $form,EntityManagerInterface $em, Request $request): ?object
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Seller $seller */
            $seller = $form->getData();

            $em->persist($seller);
            $em->flush();

            return $seller;
        }

        return null;
    }
}

<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Products;
use App\Entity\User;
use App\Form\AddCommentFormType;
use App\Service\CategoryService;
use App\Service\ProductsService;
use App\Service\ViewHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CatalogController extends AbstractController
{
    private CategoryService $categoryService;
    private ProductsService $productsService;
    private ViewHistoryService $viewHistoryService;

    public function __construct(
        CategoryService $categoryService,
        ProductsService $productsService,
        ViewHistoryService $viewHistoryService
    ) {
        $this->categoryService = $categoryService;
        $this->productsService = $productsService;
        $this->viewHistoryService = $viewHistoryService;
    }

    /**
     * @Route("/catalog", name="app_catalog")
     */
    public function catalog(PaginatorInterface $paginator, Request $request): Response
    {
        $perPage = $request->query->getInt('perPage', 20);

        $pagination = $paginator->paginate(
            $this->productsService->getAllWithSearchQuery(
                $request->query->get('q')),
            $request->query->getInt('page', 1),
            $perPage
        );

        $categories = $this->categoryService->getCategory();

        return $this->render('catalog/catalog.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/catalog/ptoducts/{slug}", name="app_catalog_show")
     */
    public function show(Products $products, $slug, Request $request, EntityManagerInterface $em): Response
    {
        $categories = $this->categoryService->getCategory();
        $sellerNames = $this->productsService->getSellerByProductId($slug);

        $user = $this->getUser();
        if ($user instanceof User) {
            $this->viewHistoryService->addToViewHistory($user, $products);
        }

        $form = $this->createForm(AddCommentFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();

            $comment
                ->setUser($user)
                ->setProduct($products)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
            ;

            $em->persist($comment);
            $em->flush();

            $this->addFlash('flash_message', 'Товар успешно создан');

            return $this->redirectToRoute('app_account');
        }

        return $this->render('catalog/product.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'sellerNames' => $sellerNames,
            'AddCommentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/catalog/compare", name="app_catalog_compare")
     */
    public function compare(): Response
    {
        $categories = $this->categoryService->getCategory();
        return $this->render('catalog/compare.html.twig', [
            'categories' => $categories,
        ]);
    }
}
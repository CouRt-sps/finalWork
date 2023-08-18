<?php

namespace App\Controller\Admin;

use App\Entity\Price;
use App\Entity\Products;
use App\Form\ProductFormType;
use App\Service\CategoryService;
use App\Service\ProductsService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @method Price|null getPrices()
 */
class ProductsController extends AbstractController
{
    private CategoryService $categoryService;
    private ProductsService $productsService;

    public function __construct(CategoryService $categoryService, ProductsService $productsService)
    {
        $this->categoryService = $categoryService;
        $this->productsService = $productsService;
    }

    /**
     * @IsGranted ("ROLE_ADMIN")
     * @Route("/admin/products", name="app_admin_products_index")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $categories = $this->categoryService->getCategory();
        $perPage = $request->query->getInt('perPage', 20);

        $pagination = $paginator->paginate(
            $this->productsService->getAllWithSearchQuery(
                $request->query->get('q')),
            $request->query->getInt('page', 1),
            $perPage
        );

        return $this->render('admin/products/index.html.twig', [
            'pagination' => $pagination,
            'perPage' => $perPage,
            'categories' => $categories,
        ]);
    }

    /**
     * @IsGranted ("ROLE_ADMIN")
     * @Route("/admin/products/create", name="app_admin_products_create")
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(ProductFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Products $products */
            $products = $form->getData();

            $price = new Price();
            $price
                ->setPrice($form->get('price')->getData())
                ->setSeller($form->get('seller')->getData())
                ->setProduct($products)
            ;

            $em->persist($products);
            $em->persist($price);

            $em->flush();

            $this->addFlash('flash_message', 'Товар успешно создан');

            return $this->redirectToRoute('app_admin_products_index');
        }

            return $this->render('admin/products/create.html.twig', [
                'productForm' => $form->createView(),
                'showError' => $form->isSubmitted(),
                'categories' => $categories,
            ]);

    }

    /**
     * @Route("/admin/products/{id}/edit", name="app_admin_products_edit")
     * @IsGranted("ROLE_ADMIN", subject="products")
     */
    public function edit(Products $products, EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(ProductFormType::class, $products);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Products $products */
            $products = $form->getData();

            /** @var UploadedFile|null $image */
            $image = $form->get('image')->getData();

            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);

            $fileName = $slugger
                ->slug($originalName)
                ->append('-' . uniqid())
                ->append('.' . $image->guessExtension())
                ->to
            ;


            $price = new Price();
            $price
                ->setPrice($form->get('price')->getData())
                ->setSeller($form->get('seller')->getData())
                ->setProduct($products)
            ;

            $em->persist($products);
            $em->persist($price);

            $em->flush();

            $this->addFlash('flash_message', 'Товар успешно изменен');

            return $this->redirectToRoute('app_admin_products_edit', ['id' => $products->getId()]);
        }

        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $form->createView(),
            'showError' => $form->isSubmitted(),
            'categories' => $categories,
        ]);
    }
}

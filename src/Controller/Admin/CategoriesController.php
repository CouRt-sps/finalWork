<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Service\CategoryService;
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
class CategoriesController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/admin/categories", name="app_admin_categories")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $categories = $this->categoryService->getCategory();
        $perPage = $request->query->getInt('perPage', 20);

        $pagination = $paginator->paginate(
            $this->categoryService->getAllWithSearchQuery(
                $request->query->get('q')),
            $request->query->getInt('page', 1),
            $perPage
        );

        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("admin/categories/create", name="app_admin_categories_create")
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(CategoryFormType::class);

        if ($category = $this->handleFormRequest($form, $em, $request)) {
           $this->addFlash('flash_message', 'Категория успешно создана');

            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin/categories/create.html.twig', [
            'categoryForm' => $form->createView(),
            'categories' => $categories,
            'showError' => $form->isSubmitted(),
        ]);
    }

    /**
     * @Route("admin/categories/{id}/edit", name="app_admin_categories_edit")
     */
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(CategoryFormType::class, $category);

        if ($category = $this->handleFormRequest($form, $em, $request)) {
           $this->addFlash('flash_message', 'Категория успешно изменена');

            return $this->redirectToRoute('app_admin_categories_edit', ['id' => $category->getId()]);
        }

        return $this->render('admin/categories/edit.html.twig', [
            'categoryForm' => $form->createView(),
            'categories' => $categories,
            'showError' => $form->isSubmitted(),
        ]);
    }
    private function handleFormRequest(FormInterface $form,EntityManagerInterface $em, Request $request): ?object
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Category $category */
            $category = $form->getData();

            $em->persist($category);
            $em->flush();

            return $category;
        }

        return null;
    }
}

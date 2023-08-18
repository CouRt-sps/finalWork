<?php

namespace App\Controller\Admin;

use App\Entity\Discounts;
use App\Form\DiscountFormType;
use App\Service\CategoryService;
use App\Service\DiscountsService;
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
class DiscountsController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/admin/discounts", name="app_admin_discounts")
     */
    public function index(Request $request, PaginatorInterface $paginator, DiscountsService $discountsService): Response
    {
        $categories = $this->categoryService->getCategory();
        $perPage = $request->query->getInt('perPage', 20);

        $pagination = $paginator->paginate(
            $discountsService->getAllWithSearchQuery(
                $request->query->get('q')),
            $request->query->getInt('page', 1),
            $perPage
        );

        return $this->render('admin/discounts/index.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("admin/discounts/create", name="app_admin_discounts_create")
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(DiscountFormType::class);

        if ($discounts = $this->handleFormRequest($form, $em, $request)) {
            $this->addFlash('flash_message', 'Скидка успешно создана');

            return $this->redirectToRoute('app_admin_discounts');
        }

        return $this->render('admin/discounts/create.html.twig', [
            'discountForm' => $form->createView(),
            'categories' => $categories,
            'showError' => $form->isSubmitted(),
        ]);
    }

    /**
     * @Route("admin/discounts/{id}/edit", name="app_admin_discounts_edit")
     */
    public function edit(Discounts $discounts, Request $request, EntityManagerInterface $em): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(DiscountFormType::class, $discounts);

        if ($discounts = $this->handleFormRequest($form, $em, $request)) {
           $this->addFlash('flash_message', 'Скидка успешно изменена');

            return $this->redirectToRoute('app_admin_discounts_edit', ['id' => $discounts->getId()]);
        }

        return $this->render('admin/discounts/edit.html.twig', [
            'discountForm' => $form->createView(),
            'categories' => $categories,
            'showError' => $form->isSubmitted(),
        ]);
    }
    private function handleFormRequest(FormInterface $form,EntityManagerInterface $em, Request $request): ?object
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Discounts $discounts */
            $discounts = $form->getData();

            $em->persist($discounts);
            $em->flush();

            return $discounts;
        }

        return null;
    }
}

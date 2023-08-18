<?php

namespace App\Controller\Admin;

use App\Service\CategoryService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        $categories = $this->categoryService->getCategory();
        return $this->render('admin/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Service\CategoryService;
use App\Service\CommentService;
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
class CommentController extends AbstractController
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @Route("/admin/comment", name="app_admin_comment")
     */
    public function index(Request $request, PaginatorInterface $paginator, CommentService $commentService): Response
    {
        $categories = $this->categoryService->getCategory();
        $perPage = $request->query->getInt('perPage', 20);

        $pagination = $paginator->paginate(
            $commentService->getAllWithSearchQuery(
                $request->query->get('q')),
            $request->query->getInt('page', 1),
            $perPage
        );

        return $this->render('admin/comment/index.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination,
        ]);
    }
    /**
     * @Route("admin/comment/create", name="app_admin_comment_create")
     */
    public function create(EntityManagerInterface $em, Request $request): Response
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(CommentFormType::class);

        if ($comment = $this->handleFormRequest($form, $em, $request)) {
            $this->addFlash('flash_message', 'Комментарий успешно создан');

            return $this->redirectToRoute('app_admin_comment');
        }

        return $this->render('admin/comment/create.html.twig', [
            'commentForm' => $form->createView(),
            'categories' => $categories,
            'showError' => $form->isSubmitted(),
        ]);
    }

    /**
     * @Route("admin/comment/{id}/edit", name="app_admin_comment_edit")
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $em)
    {
        $categories = $this->categoryService->getCategory();
        $form = $this->createForm(CommentFormType::class, $comment);

        if ($comment = $this->handleFormRequest($form, $em, $request)) {
            $this->addFlash('flash_message', 'Комментарий успешно изменен');

            return $this->redirectToRoute('app_admin_comment_edit', ['id' => $comment->getId()]);
        }

        return $this->render('admin/comment/edit.html.twig', [
            'commentForm' => $form->createView(),
            'categories' => $categories,
            'showError' => $form->isSubmitted(),
        ]);
    }
    private function handleFormRequest(FormInterface $form,EntityManagerInterface $em, Request $request): ?object
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();

            if ($comment->getCreatedAt() > $comment->getUpdatedAt()) {
                $this->addFlash('error', 'дата редактирования раньше даты создания');
                return null;
            }

            $em->persist($comment);
            $em->flush();

            return $comment;
        }

        return null;
    }
}

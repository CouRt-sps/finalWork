<?php

namespace App\Service;

use App\Repository\CommentRepository;

class CommentService
{
    private CommentRepository $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    public function getAllWithSearchQuery(?string $search): object
    {
        return $this->commentRepository->findAllWithSearchQuery($search);
    }
}
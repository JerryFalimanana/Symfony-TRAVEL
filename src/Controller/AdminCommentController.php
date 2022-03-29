<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(CommentRepository $repo): Response
    {
        // injection de dépendance
        // $repo = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $repo->findAll();

        return $this->render('back_office/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostRepository;
use App\Entity\Post;
use App\Entity\Comment;
use App\Repository\CommentRepository;

class CommentController extends AbstractController
{
    #[Route('/blog/{id}/comments', name: 'blog')]
    public function comments($id, CommentRepository $commentRepository, PostRepository $PostRepository): Response
    {
        $post = $PostRepository->find($id);
        $comments = $commentRepository->findByPost($id);
        return $this->render('comment/index.html.twig',['comments' => $comments, 'post' => $post]);
    }
}

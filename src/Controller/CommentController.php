<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostRepository;
use App\Entity\Post;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Form\CommentType;

class CommentController extends AbstractController
{
     //comment on a post
     #[Route('/addComment/{postId}', name: 'addComment')]
     public function addcomment(EntityManagerInterface $entityManager,$postId,Request $request,
     PostRepository $postRepository): Response
     {
         $post=$postRepository->find($postId);
         if($post)
         {
             $comment = new Comment();
             $form = $this->createForm(CommentType::class, $comment);
             $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {
                 $comment->setDate(new \DateTimeImmutable());
                 $comment->setPost($post);
                 $post->addComment($comment);
                 $entityManager->persist($comment);
                 $entityManager->flush();
                 $this->addFlash('success', 'le commantaire a été ajouté avec succès');
                  return $this->redirectToRoute('post_comments',['id' => $postId]);
             }
         return $this->render('comment/add.html.twig', ['form' => $form->createView(),]);
         }
     }
        
     #[Route('/blog/{id}/comments', name: 'post_comments')]
     public function comments($id, CommentRepository $commentRepository, PostRepository $PostRepository): Response
     {
         $post = $PostRepository->find($id);
         $comments = $commentRepository->findByPost($id);
         return $this->render('comment/show.html.twig',['comments' => $comments, 'post' => $post]);
     }
}

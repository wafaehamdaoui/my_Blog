<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostRepository;
use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Entity\Comment;
use App\Repository\CategoryRepository;
use App\Entity\Category;
use App\Form\PostType;


class PostController extends AbstractController
{
    // show all posts
    #[Route('/blog/all', name: 'all')]
    public function all(EntityManagerInterface $entityManager,
    PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig',
        ['posts' => $posts]);
    }
    //add a new post
    #[Route('/AddNewPost', name: 'NewPost')]
    public function AddNewPost(Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTimeImmutable());
            $post->setUpdatedAt(new \DateTimeImmutable() );
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'le Post a été ajouté avec succès');
            return $this->redirectToRoute('all');
        }
        return $this->render('post/add.html.twig', ['form' => $form->createView(),]);
    }
    //show a post
    #[Route('/more/{id}', name: 'more')]
    public function more($id, PostRepository $postRepository): Response
    {
        $post=$postRepository->find($id);
        return $this->render('post/show.html.twig', 
        ['post' => $post]);
    }
    // delete a post
    #[Route('/delete/{id}', name: 'blog_delete')]
    public function delete($id,EntityManagerInterface $entityManager,
    PostRepository $postRepository): Response
    {
        $post=$postRepository->find($id);
        if($post){
            $entityManager->remove($post);
            $entityManager->flush();
        }
        return $this->redirectToRoute('all');
    }

    // find posts added by an author
    #[Route('/blog/author/{name}', name: 'blog_by_author')]
    public function searchByAuthor(PostRepository $postRepository, $name): Response
    {
        $posts = $postRepository->findByAuthor($name);
        return $this->render('post/index.html.twig',['posts' => $posts]);
    }

    //find posts of a  specific category 
    #[Route('/blog/category/{id}', name: 'blog_by_category')]
    public function searchByCategory(PostRepository $postRepository, $id): Response
    {
        $posts = $postRepository->findByCategory($id);
        return $this->render('post/index.html.twig',['posts' => $posts]);
    }
    
    // remove all posts of a specific category and the category
    #[Route('/blog/category/remove/{id}', name: 'category_delete_post')]
    public function categoryDeletePost($id,EntityManagerInterface $entityManager,
    CategoryRepository $categoryRepository, PostRepository $postRepository): Response
    {
        $category=$categoryRepository->find($id);
        if($category){
            $entityManager->remove($category); 
            $entityManager->flush();
        }
        $posts = $postRepository->findByCategory($id);
        if ($posts) {
            foreach ($posts as $post) {
                $entityManager->remove($post); 
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('all');
    }

    #[Route('/blog/remove/{post}/{comment}', name: 'comment_delete')]
    public function commentdelete($post,$comment,EntityManagerInterface $entityManager,
    CommentRepository $commentRepository): Response
    {
        $comm=$commentRepository->find($comment);
        if($comm){
            $entityManager->remove($comm);
            $entityManager->flush();
        }
        return $this->redirectToRoute('post_comments',['id' => $post]);
    }
    #[Route('/blog/removeComments', name: 'comments_delete')]
    public function commentdeleteAll(EntityManagerInterface $entityManager,
    CommentRepository $commentRepository): Response
    {
        $comments=$commentRepository->findAll();
        if($comments){
            foreach ($comments as $comment) {
                $entityManager->remove($comment);
            }
            $entityManager->flush();
        }
        return $this->redirectToRoute('allComments');
    }

    #[Route('/blog/remove/{post}', name: 'comment_delete_post')]
    public function commentDeletePost($post,EntityManagerInterface $entityManager,
    CommentRepository $commentRepository): Response
    {
        $comments=$commentRepository->findByPost($post);
        if($comments){
            foreach ($comments as $comment) {
                $entityManager->remove($comment);
                $entityManager->flush();
            } 
        }
        return $this->redirectToRoute('post_comments',['id',$post]);
    }

    #[Route('/blog/comments/author/{name}', name: 'author_removeAll')]
    public function removeAll(PostRepository $postRepository,EntityManagerInterface $entityManager,
                                CommentRepository $commentRepository, $name): Response
    {
        $posts = $postRepository->findByAuthor($name);
        if($posts){
            foreach ($posts as $post) {
                $comments = $commentRepository->findByPost($post->getId());
                if($comments){
                    foreach ($comments as $comment) {
                        $entityManager->remove($comment);
                        $entityManager->flush();
                    } 
                }
                $entityManager->remove($post);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('allComments');
    }
    #[Route('/comment/all', name: 'allComments')]
    public function allComments(EntityManagerInterface $entityManager,
    CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll();
        return $this->render('comment/index.html.twig',
        ['comments' => $comments]);
    }

    #[Route('/addCategory', name: 'addCategory')]
    public function addCategory(EntityManagerInterface $entityManager,
    CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $category->setName('Category 1');
        $entityManager->persist($category);
        $entityManager->flush();
        return $this->redirectToRoute('all');
    }
    

}

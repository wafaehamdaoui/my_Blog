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

class PostController extends AbstractController
{
    #[Route('/post', name: 'blog_index')]
    public function index(EntityManagerInterface $entityManager,
    PostRepository $postRepository): Response
    {
       // creation of a new post ojbject
       $post = new Post();
       // definir proprieties
       $post->setTitle('Will ChatGPT take your job — and millions of others?');
       $post->setContent('The latest AI wave will disrupt the workplace. Teachers could be most affected. But it could end up creating more jobs.
       In the four months since its November 30 launch, ChatGPT has shown the ability to perform a wide range of tasks,
        from cracking the bar and medical licensing exams in the United States to writing emails and songs, building apps, and more.
       t a time when the International Labour Organization already estimates that 208 million people will be unemployed in 2023, 
       will this new wave of AI dramatically increase joblessness? Which jobs would these tools potentially replace? What is the future of work?
       The short answer: ChatGPT and its rival AI models could dramatically disrupt the labour market, including replacing routine jobs in some sectors.
        But overall, the technology could enhance productivity and complement human workers, instead of leading to unemployment, experts told Al Jazeera.');
       $post->setAuthor( 'Meryem HNIDA');
       $post->setAuthorRole( "EIDIA's Teacher");
       $post->setCreatedAt(new \DateTime('2023-3-28'));
       $post->setUpdatedAt(new \DateTime('2023-3-29'));
       $post->setUrl('https://newuniversity.org/wp-content/uploads/2023/02/shutterstock_2241913405-1-1024x683.jpg');
       $entityManager->persist($post);
       $entityManager->flush();
       // creation of a new post ojbject
       $post = new Post();
       // definir proprieties
       $post->setTitle('Artificial intelligence: friend or foe?');
       $post->setContent('Experts predict AI will be more intelligent than humans one day. What can humans do about it?
       Mo Gawdat, author and former chief business officer at Google X, predicts that AI will become a billion times smarter
        than humans. The time is now, he says, to influence it with the right ethics to have humanity’s best interests in mind. 
        Artificial intelligence is becoming unavoidable with smart devices in every aspect of our lives, and AI image and text 
        generation reaching new heights. So, is it time to make friends with AI?');
       $post->setAuthor( 'Wafae HAMDAOUI');
       $post->setAuthorRole( "EIDIA's Student");
       $post->setCreatedAt(new \DateTime('2023-3-20'));
       $post->setUpdatedAt(new \DateTime('2023-3-20'));
       $post->setUrl('https://www.aljazeera.com/wp-content/uploads/2023/01/GettyImages-1215125159.jpg?resize=770%2C513&quality=80');
       $entityManager->persist($post);
       $entityManager->flush();

       // creation of a new post ojbject
       $post = new Post();
       // definir proprieties
       $post->setTitle('AL JAZEERA CONFERENCE ON ARTIFICIAL INTELLIGENCE AND MEDIA ');
       $post->setContent("Al Jazeera Media Institute concluded on Monday, March 13, the sessions of the Artificial Intelligence in Media Conference, with the participation of a group of specialists in artificial intelligence, media workers and academia.
       The opening, the first, and third sessions were moderated by Rawaa Auge, with the participation of the robot 'Pepper' and the institute's artificial intelligence presenter 'Ibtikar'.
       The conference saw the screening of a documentary film dealing with artificial intelligence that presents Al Jazeera as an example.
       The Institute concluded the conference with a workshop entitled Artificial Intelligence and Cybersecurity Workshop, with the active participation of the trainees, whose number reached about a hundred.
       ");
       $post->setAuthor('Hasouunti');
       $post->setAuthorRole( "EIDIA's Administrator");
       $post->setCreatedAt(new \DateTime('2023-3-14'));
       $post->setUpdatedAt(new \DateTime('2023-3-14'));
       $post->setUrl('https://institute.aljazeera.net/sites/default/files/2023/%D8%A7%D9%84%D8%AC%D8%B2%D9%8A%D8%B1%D8%A9%20%D9%88%D8%A7%D9%84%D8%B0%D9%83%D8%A7%D8%A1.JPG');
       $entityManager->persist($post);
       $entityManager->flush();
       //flash message
       $this->addFlash('success', 'Le post a été enregistré avec succès.');

       // récupération de tous les posts
       $posts = $postRepository-> findAll();
       //équivalent à SELECT * FROM post
       return $this->render('post/index.html.twig',
       ['posts' => $posts]);
    }
    #[Route('/more', name: 'more')]
    public function more(EntityManagerInterface $entityManager,
    PostRepository $postRepository): Response
    {
        $post=$postRepository->find(73);
        return $this->render('post/show.html.twig', 
        ['post' => $post]);
    }
    #[Route('/delete', name: 'blog_delete')]
    public function delete(EntityManagerInterface $entityManager,
    PostRepository $postRepository): Response
    {
        $post=$postRepository->find(73);
        if($post){
            $entityManager->remove($post);
            $entityManager->flush();
        }
        $posts = $postRepository-> findAll();
        //équivalent à SELECT * FROM post
        return $this->render('post/index.html.twig',
        ['posts' => $posts]);
    }

    #[Route('/add', name: 'blog_add')]
    public function new(Request $request): Response
    {
        $blog = new Post();
        $form = $this->createForm(BlogType::class,$blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('post/add.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/blog/all', name: 'all')]
    public function all(EntityManagerInterface $entityManager,
    PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig',
        ['posts' => $posts]);
    }

    #[Route('/blog/{id}', name: 'blog')]
    public function show($id, PostRepository $postRepository): Response
    {
        $post = $postRepository->find($id);

        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
    #[Route('/blog/author/{name}', name: 'blog_by_author')]
    public function searchByAuthor(PostRepository $postRepository, $name): Response
    {
        $posts = $postRepository->findByAuthor($name);
        return $this->render('post/index.html.twig',['posts' => $posts]);
    }

    #[Route('/blog/{id}/comments', name: 'blog')]
    public function comments($id, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findByPost($id);

        return $comments;
    }

    #[Route('/addComment', name: 'addComment')]
    public function add(EntityManagerInterface $entityManager,
    PostRepository $postRepository): Response
    {
        $post=$postRepository->find(13);
        if($post)
        {
            $comment = new Comment();
            $comment->setContent('Commentaire 1');
            $comment->setAuthor('Meriem HNIDA');
            $comment->setDate(new \DateTimeImmutable());
            $post->addComment($comment);
            $entityManager->persist($post);
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig',
        ['posts' => $posts]);
       }
}

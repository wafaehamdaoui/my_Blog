<?php
namespace App\Form;
use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class)
        ->add('content', TextareaType::class, ['attr' => ['rows' => 5]])
        ->add('url', TextType::class)
        ->add('author', TextType::class)
        ->add('authorRole', TextType::class)
        //->add('category', ChoiceType::class, [
         //   'choices' => [
           //     'Category A' => 'a',
               // 'Category B' => 'b',
               // 'Category C' => 'c',
         //   ],
     //   ])
        //;
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'name',
        ])
    ;
    }
}
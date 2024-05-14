<?php

namespace App\Form\Admin;

use App\Entity\Article;
use App\Entity\ArticleCategorie;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'photo',
                'required'  =>  false,
                'allow_delete' =>  false,
                'download_label'     =>  false,
                'image_uri'     =>  false,
                'download_uri'     =>  false,
                'imagine_pattern'   =>  'largeavatar',
                'required' => false
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'help' => "Titre de l'article",
                'attr' => [
                    'placeholder' => "Titre de l'article"
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('tags', EntityType::class, [
                'label' => "Mots clés (Tags)",
                'class' => Tag::class,
                'placeholder'   =>  "Sélectionnez plus d'option...",
                'multiple' => true,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (TagRepository $tag) {
                    return $tag->createQueryBuilder('t')->orderBy('t.name', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-tags'
                ],
            ])
            ->add('categorie', EntityType::class, [
                'label' => "Publier dans la bonne catégorie",
                'placeholder'   =>  "Sélectionnez une option...",
                'class' => ArticleCategorie::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('resume', TextareaType::class, [
                'label' => false,
                'help' => "Décrivez l'article en quelques lignes",
                'required' => false,
                'attr' => [
                    'placeholder' => "Décrivez l'article en quelques lignes.",
                    'class' => 'focus'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => "Contenu de l'article",
                'help' => "Décrivez le contenu de l'article.",
                'required' => false,
                'attr' => [
                    'placeholder' => "Décrivez le contenu de l'article.",
                    'class' => 'mce'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

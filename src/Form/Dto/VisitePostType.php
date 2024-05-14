<?php

namespace App\Form\Dto;

use App\Entity\Categorie;
use App\Entity\Dto\Post as DtoPost;
use App\Entity\SousCategorie;
use App\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VisitePostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Que cherchez-vous ?",
                    'class' => 'bg-white'
                ],
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'label' => false,
                'placeholder'   =>  "Toute les catégorie...",
                'class' => Categorie::class,
                'query_builder' => function (CategorieRepository $cat) {
                    return $cat->createQueryBuilder('c')->orderBy('c.name', 'ASC')->andWhere('c.complet = :complet')->setParameter('complet', 1);
                },
                'required' => false,
                'attr'   =>  [
                    'class' => "bg-white p-2",
                ],
            ])->add('promo', CheckboxType::class, [
                'label' => "En promotion",
                'required' => false,
            ])->add('etat', ChoiceType::class, [
                'label' => "Etat de le service",
                'placeholder' => false,
                'choices' => [
                    'Neuf' => 'Neuf',
                    'Occasion' => 'Occasion'
                ],
                'required' => false,
                'expanded' => true
            ])->add('negociable', CheckboxType::class, [
                'label' => "Prix négociable",
                'required' => false,
            ])->add('livraison', CheckboxType::class, [
                'label' => "Livraison gratuite",
                'required' => false,
            ])->add('minPrice', NumberType::class, [
                'label' => 'Minimun',
                'required' => false,
                'attr' => [
                    'placeholder' => "Minimun"
                ]
            ])->add('maxPrice', NumberType::class, [
                'label' => 'Maximum',
                'required' => false,
                'attr' => [
                    'placeholder' => "Maximum"
                ]
            ])
            ->add('limit', ChoiceType::class, [
                'placeholder' => false,
                'label' => false,
                'choices' => [
                    'Affichage de 12' => 12,
                    'Affichage de 20' => 20,
                    'Affichage de 30' => 30,
                    'Affichage de 40' => 40,
                    'Affichage de 50' => 50,
                    'Affichage de 60' => 60,
                ],
                'required' => false,
            ]);

        /*$builder->get('categorie')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {

                $form = $event->getForm();

                $this->addCategorieFields($form->getParent(), $form->getData());
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {

                $data = $event->getData();
                /** @var SousCategorie $souscategorie  *
                $souscategorie = $data->getSouscategorie(); //dd($souscategorie);

                $form = $event->getForm();

                if ($souscategorie) {

                    $categorie = $souscategorie->getCategorie();

                    $this->addCategorieFields($form, $categorie);

                    $form->get('categorie')->setData($categorie);
                    $form->get('souscategorie')->setData($souscategorie);
                } else {
                    $this->addCategorieFields($form, null);
                }
            }
        );*/
    }

    /**
     * @param Categorie $categorie
     * @return void
     */
    private function addCategorieFields($form, ?Categorie $categorie)
    {

        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'souscategorie',
            EntityType::class,
            null,
            [
                'label' => "Sous-catégorie de le service",
                'class' =>  SousCategorie::class,
                'auto_initialize'   =>  false,
                'placeholder'   =>  "Sélectionnez une option...",
                'choices'   =>  $categorie ? $categorie->getSousCategories() : [],
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank(),
                ]
            ]
        );

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DtoPost::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}

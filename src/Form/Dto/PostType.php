<?php

namespace App\Form\Dto;

use App\Entity\Categorie;
use App\Entity\Dto\Post as DtoPost;
use App\Entity\SousCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Je cherche...",
                    'class' => "bg-white",
                ],
                'required' => false,
            ])
            ->add('souscategorie', EntityType::class, [
                'label' => false,
                'placeholder'   =>  "Toute les catégorie...",
                'class' => SousCategorie::class,
                'required' => false,
                'expanded' => true
            ])->add('promo', CheckboxType::class, [
                'label' => "En promotion",
                'required' => false,
            ])->add('etat', ChoiceType::class, [
                'label' => false,
                'placeholder'   =>  "Etat...",
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
            ])
            ->add('online', ChoiceType::class, [
                'placeholder' => "Statut",
                'expanded' => true,
                'label' => false,
                'choices' => [
                    'En ligne' => 1,
                    'Hors ligne' => 0
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

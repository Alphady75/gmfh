<?php

namespace App\Form\User;

use App\Entity\Categorie;
use App\Entity\Post;
use App\Entity\SousCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('medias', CollectionType::class, [
                'label' => false,
                'entry_type' => MediaType::class,
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false
            ])
            ->add('name', TextType::class, [
                'label' => false,
                'help' => "Utilisez un bref titre",
                'attr' => [
                    'placeholder' => "Titre de l'annonce"
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('tarif', IntegerType::class, [
                'label' => false,
                'help' => "Mettre un prix raisonnable",
                'required' => false,
                'attr' => [
                    'placeholder' => "Prix de l'annonce",
                    'min' => 1,
                    'class' => "focus",
                ]
            ])->add('devise', ChoiceType::class, [
                'label' => false,
                'help' => "Devise (monnaie)",
                'choices' => [
                    '$ (Dolar)' => '$ (Dolar)',
                    '€ (EURO)' => '€ (EURO)',
                    'XFA (FCFA)' => 'XFA (FCFA)',
                ],
                'placeholder' => false,
                'required' => false
            ])
            ->add('tarifPromo', IntegerType::class, [
                'label' => "Prix promotionel",
                'help' => "Mettre un prix raisonnable",
                'required' => false,
                'attr' => [
                    'placeholder' => "Prix promotionel",
                    'min' => 1,
                    'class' => "pripromo",
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'help' => "Décrivez votre annonce en quelques lignes.",
                'required' => false,
                'attr' => [
                    'placeholder' => "Décrivez votre annonce en quelques lignes.",
                    'class' => 'mceu'
                ]
            ])
            ->add('categorie', EntityType::class, [
                'label' => "Que souhaitez-vous vendre ?",
                'placeholder'   =>  "Sélectionnez une option...",
                'class' => Categorie::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('souscategorie', EntityType::class, [
                'label' => "Publier dans la bonne catégorie",
                'placeholder'   =>  "Sélectionnez une option...",
                'class' => SousCategorie::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('promo', CheckboxType::class, [
                'label' => "En promotion",
                'required' => false,
            ])->add('etat', ChoiceType::class, [
                'label' => "Etat de l'annonce",
                'choices' => [
                    'Neuf' => 'Neuf',
                    'Occasion' => 'Occasion'
                ],
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('statut', ChoiceType::class, [
                'label' => "Statut",
                'choices' => [
                    'En vente' => 'En vente',
                    'A louer' => 'A louer',
                    'Réservation' => 'Réservation'
                ],
                'placeholder' => false,
                'required' => false,
                'expanded' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('negociable', CheckboxType::class, [
                'label' => "Prix négociable",
                'required' => false,
            ])->add('livraison', CheckboxType::class, [
                'label' => "Livraison gratuite",
                'required' => false,
            ])
            ->add('boosted', CheckboxType::class, [
                'label' => "Booster",
                'help' => "Dennez de la visibilité à votre annonce",
                'required' => false
            ]);

        $builder->get('categorie')->addEventListener(
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
                /** @var SousCategorie $souscategorie  */
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
        );
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
                'label' => "Sous-catégorie de l'annonce",
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
            'data_class' => Post::class,
        ]);
    }
}

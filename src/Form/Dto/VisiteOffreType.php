<?php

namespace App\Form\Dto;

use App\Entity\Categorie;
use App\Entity\Dto\Offre;
use App\Entity\Horaires;
use App\Entity\SecteursActivite;
use App\Entity\SousCategorie;
use App\Repository\HorairesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VisiteOffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Que cherchez-vous ?"
                ],
                'required' => false,
            ])
            ->add('secteur', EntityType::class, [
                'label' => false,
                'placeholder'   =>  "Tout les secteurs...",
                'attr'   =>  [
                    'class' => "",
                ],
                'class' => SecteursActivite::class,
                'required' => false,
            ])->add('localisation', TextType::class, [
                'label' => false,
                'required' => false,
                'attr'   =>  [
                    'placeholder'   =>  "Localisation...",
                ],
            ])
            ->add('typeContrat', ChoiceType::class, [
                'label' => false,
                'placeholder' => "Type de contrat",
                'choices' => [
                    "Temps plein" => "Temps plein",
                    "Temps partiel" => "Temps partiel",
                    "Permanent" => "Permanent",
                    "Contrat à durée déterminée (CDD)" => "Contrat à durée déterminée (CDD)",
                    "Contrat à durée indéterminée (CDI)" => "Contrat à durée indéterminée (CDI)",
                    "Occasionnel" => "Occasionnel",
                    "Saisonnier" => "Saisonnier",
                    "Pigiste/Freelance" => "Pigiste/Freelance",
                    "Apprentissage" => "Apprentissage",
                    "Stage/Coop" => "Stage/Coop"
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('horaires', EntityType::class, [
                'label' => false,
                'class' => Horaires::class,
                'placeholder'   =>  false,
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (HorairesRepository $exp) {
                    return $exp->createQueryBuilder('h')->orderBy('h.id', 'ASC');
                },
                'attr' => [
                    'class' => ''
                ],
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
            'data_class' => Offre::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}

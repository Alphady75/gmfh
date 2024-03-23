<?php

namespace App\Form\Dto;

use App\Entity\Categorie;
use App\Entity\Dto\Offre;
use App\Entity\SecteursActivite;
use App\Entity\SousCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OffreType extends AbstractType
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
                'class' => SecteursActivite::class,
                'required' => false,
            ])->add('status', ChoiceType::class, [
                'label' => false,
                'placeholder'   =>  "Statut...",
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif'
                ],
                'required' => false,
                'expanded' => false
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

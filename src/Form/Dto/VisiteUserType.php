<?php

namespace App\Form\Dto;

use App\Entity\Categorie;
use App\Entity\Competence;
use App\Entity\Dto\User;
use App\Entity\Langue;
use App\Entity\SecteursActivite;
use App\Entity\Service;
use App\Entity\SousCategorie;
use App\Entity\Ville;
use App\Repository\CompetenceRepository;
use App\Repository\LangueRepository;
use App\Repository\ServiceRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VisiteUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => "Que cherchez-vous ?",
                    'class' => "bg-white",
                ],
                'required' => false,
            ])
            ->add('secteur', EntityType::class, [
                'label' => false,
                'placeholder'   =>  "Tous les secteurs...",
                'attr'   =>  [
                    'class' => "bg-white",
                ],
                'class' => SecteursActivite::class,
                'required' => false,
                'expanded' => false,
            ])->add('localisation', TextType::class, [
                'label' => false,
                'required' => false,
                'attr'   =>  [
                    'placeholder'   =>  "Localisation...",
                ],
            ])->add('langues', EntityType::class, [
                'label' => "Compétence linguistiques",
                'class' => Langue::class,
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (LangueRepository $lang) {
                    return $lang->createQueryBuilder('l')->orderBy('l.id', 'DESC')->setMaxResults(10);
                },
                'attr' => [
                    'placeholder' => "Toute les langues",
                    'class' => ''
                ],
            ])->add('competences', EntityType::class, [
                'label' => "Compétences",
                'class' => Competence::class,
                'placeholder' => "Toute les compétences",
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (CompetenceRepository $comp) {
                    return $comp->createQueryBuilder('c')->orderBy('c.id', 'DESC')->setMaxResults(10);
                },
                'attr' => [
                    'class' => ''
                ],
            ])
            ->add('services', EntityType::class, [
                'label' => "Services",
                'class' => Service::class,
                'placeholder' => "Tout les services",
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (ServiceRepository $ser) {
                    return $ser->createQueryBuilder('s')->orderBy('s.id', 'DESC')->setMaxResults(10);
                },
                'attr' => [
                    'class' => ''
                ],
            ])
            ->add('villes', EntityType::class, [
                'label' => "Périmettre",
                'class' => Ville::class,
                'placeholder' => "Périmettre",
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (VilleRepository $vil) {
                    return $vil->createQueryBuilder('v')->orderBy('v.id', 'DESC')->setMaxResults(10);
                },
                'attr' => [
                    'class' => ''
                ],
            ])
            /*->add('limit', ChoiceType::class, [
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
            ])*/;

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
            'data_class' => User::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}

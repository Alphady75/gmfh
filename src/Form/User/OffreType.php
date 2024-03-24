<?php

namespace App\Form\User;

use App\Entity\Booster;
use App\Entity\Experiences;
use App\Entity\Horaires;
use App\Entity\Offre;
use App\Entity\SecteursActivite;
use App\Entity\SousSecteursActivite;
use App\Repository\ExperiencesRepository;
use App\Repository\HorairesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitulePost', TextType::class, [
                'label' => "Titre du poste",
                'help' => "Ex: Assistante de direction",
                'attr' => [
                    'placeholder' => "Ex: Assistante de direction",
                    'class' => "",
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('typeContrat', ChoiceType::class, [
                'label' => "Type de contrat",
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
                'attr' => [
                    'class' => 'select2-type',
                    'placeholder' => 'Ex: Assistante de Direction',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'label' => "Quelle est la date de début prévue pour ce poste?",
                'help' => "Date debut de contrat",
                'widget' => 'single_text',
                'attr' => [
                    'class' => ""
                ],
                'required' => false,
            ])
            ->add('dateFin', DateType::class, [
                'label' => "Date de cloture des candidatures",
                'help' => "Date de cloture des candidatures",
                'widget' => 'single_text',
                'attr' => [
                    'class' => ""
                ],
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => "Titre de l'offre",
                'attr' => [
                    'placeholder' => "Ex: recrutement du personnel"
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'mce',
                    'placeholder' => 'Donnez plus de détails à votre offre...',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])->add('experiences', EntityType::class, [
                'class' => Experiences::class,
                'placeholder'   =>  "Sélectionnez une option...",
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (ExperiencesRepository $exp) {
                    return $exp->createQueryBuilder('e')->orderBy('e.name', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-experiences'
                ],
            ])->add('horaires', EntityType::class, [
                'label' => false,
                'class' => Horaires::class,
                'placeholder'   =>  "Sélectionnez une option...",
                'multiple' => true,
                'label' => false,
                'required' => false,
                'by_reference' => false,
                'query_builder' => function (HorairesRepository $exp) {
                    return $exp->createQueryBuilder('h')->orderBy('h.id', 'ASC');
                },
                'attr' => [
                    'class' => 'js-select2-horaires'
                ],
            ])
            ->add('localisation', TextType::class, [
                'label' => "Quelle est l'adresse postale de ce lieu? ",
                'required' => false,
                'attr' => [
                    'class' => "",
                    'placeholder' => "Quelle est l'adresse postale de ce lieu? "
                ],
            ])
            ->add('lieuTravail', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    "En personne (emplacement précis)" => "En personne",
                    "Emplacement général (Dans une zone limité)" => "Emplacement général",
                    "Télétravail (Aucun travail sur place n'est demander)" => "Télétravail Le poste s'exerce à distance",
                    "Télétravail hybride (Le poste combine le travail sur site à une adresse précise et du télétravail)." => "Télétravail hybride",
                    "Déplacements fréquents (Le poste implique les déplacements reguliers)." => "Déplacements fréquents",
                ],
                'expanded' => true,
                'attr' => [
                    'class' => "",
                    'placeholder' => 'Sélectionnez une option'
                ],
            ])
            ->add('langues', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    "Anglais" => "Anglais",
                    "Français" => "Français",
                ],
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Sélectionnez une option'
                ],
            ])
            ->add('effectifRecrutement', TextType::class, [
                'label' => "Nombre de personnes à recruter pour ce poste",
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => "",
                    'min' => 1,
                    'value' => 1,
                    'placeholder' => 'Nombre de personnes à recruter pour ce poste...'
                ],
            ])
            ->add('infosContrat', TextareaType::class, [
                'label' => "Informations du contrat",
                'help' => "Fournir plus d'information du contrat",
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'class' => "focus",
                    'placeholder' => 'Informations du contrat...'
                ],
            ])
            ->add('secteuractivite', EntityType::class, [
                'label' => "Secteurs d'activités",
                'placeholder'   =>  "Sélectionnez une option...",
                'class' => SecteursActivite::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('soussecteuractivite', EntityType::class, [
                'label' => "Sous secteur d'activité",
                'placeholder'   =>  "Sélectionnez une option...",
                'class' => SousSecteursActivite::class,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('status', ChoiceType::class, [
                'label' => false,
                'placeholder' => "Statut de l'offre",
                'choices' => [
                    "Actif (Les utilisateurs pourront postuler à cette offre)" => "Actif",
                    "Inactif (Les utilisateurs ne pourront pas postuler à cette offre)" => "Inactif",
                ],
                'multiple' => false,
                'expanded' => true,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('boosted', CheckboxType::class, [
                'label' => "Booster",
                'help' => "Dennez de la visibilité à votre offre",
                'required' => false
            ])
            ->add('salaire', NumberType::class, [
                'label' => "Salaire moyen",
                'help' => "La prétention salariale peut être définie comme la rémunération à laquelle vous proposez pour ce poste.",
                'required' => false,
                'attr' => [
                    'class' => "",
                    'placeholder' => "Salaire moyen (Ex: $2000)"
                ],
            ])->add('devise', ChoiceType::class, [
                'label' => "Devise (monnaie)",
                'help' => "Devise (monnaie)",
                'choices' => [
                    '$' => '$',
                    '€' => '€',
                    'FCFA' => 'FCFA',
                ],
                'placeholder' => false,
                'required' => false
            ])
            ->add('periodicite', ChoiceType::class, [
                'label' => "Periodicité",
                'help' => "Ex: rémunération par heure",
                'placeholder' => "Rémunération par...",
                'required' => false,
                'choices' => [
                    'Heure' => 'Rémunération par heure',
                    'Jour' => 'Rémunération par jour',
                    'Mois' => 'Rémunération par mois',
                    'Année' => 'Rémunération par année',
                ],
            ])
            ->add('qualification', TextType::class, [
                'label' => "Qualification pour ce poste ?",
                'help' => "Facultatif",
                'required' => false,
                'attr' => [
                    'class' => "",
                    'placeholder' => "Ex: Doctorat..."
                ],
            ])
            ->add('anneeExperience', TextType::class, [
                'label' => "Nombre d'année d'expérience pour le poste",
                'help' => "Facultatif",
                'required' => false,
                'attr' => [
                    'class' => "",
                    'placeholder' => "Ex: 2ans..."
                ],
            ]);

            $builder->get('secteuractivite')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
    
                    $form = $event->getForm();
    
                    $this->addSecteursFields($form->getParent(), $form->getData());
                }
            );
    
            $builder->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event) {
    
                    $data = $event->getData();
                    /** @var SousSecteursActivite $soussecteuractivite  */
                    $soussecteuractivite = $data->getSousSecteuractivite(); //dd($soussecteuractivite);
    
                    $form = $event->getForm();
    
                    if ($soussecteuractivite) {
    
                        $secteuractivite = $soussecteuractivite->getSecteursActivite();
    
                        $this->addSecteursFields($form, $secteuractivite);
    
                        $form->get('secteuractivite')->setData($secteuractivite);
                        $form->get('soussecteuractivite')->setData($soussecteuractivite);
                    } else {
                        $this->addSecteursFields($form, null);
                    }
                }
            );
    }

    /**
     * @param SecteursActivite $secteuractivite
     * @return void
     */
    private function addSecteursFields($form, ?SecteursActivite $secteuractivite)
    {

        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'soussecteuractivite',
            EntityType::class,
            null,
            [
                'label' => "Sous secteur d'activité",
                'class' =>  SousSecteursActivite::class,
                'auto_initialize'   =>  false,
                'placeholder'   =>  "Sélectionnez une option...",
                'choices'   =>  $secteuractivite ? $secteuractivite->getSousSecteursActivites() : [],
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'class' => '',
                ]
            ]
        );

        $form->add($builder->getForm());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}

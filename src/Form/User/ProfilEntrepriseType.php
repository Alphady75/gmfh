<?php

namespace App\Form\User;

use App\Entity\Service;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfilEntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $step = $options['step'];

        if ($step == 'registration') {

            $builder
                ->add('logoFile', VichImageType::class, [
                    'label' => 'logo',
                    'required'  =>  false,
                ])->add('societe', TextType::class, [
                    'label' => "Nom de la société",
                    'attr' => [
                        'placeholder' => "Nom de la société",
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])->add('niu', TextType::class, [
                    'label' => "NIU",
                    'attr' => [
                        'placeholder' => "Numéro d'identification unique (NIU)",
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])->add('services', EntityType::class, [
                    'label' => false,
                    'class' => Service::class,
                    'multiple' => true,
                    'required' => false,
                    'by_reference' => false,
                    'query_builder' => function (EntityRepository $se) {
                        return $se->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                    },
                    'attr' => [
                        'class' => 'js-select2-services'
                    ],
                ])->add('villes', EntityType::class, [
                    'label' => false,
                    'class' => Ville::class,
                    'multiple' => true,
                    'required' => false,
                    'by_reference' => false,
                    'query_builder' => function (EntityRepository $vil) {
                        return $vil->createQueryBuilder('v')->orderBy('v.name', 'ASC');
                    },
                    'attr' => [
                        'class' => 'js-select2-villes'
                    ],
                ])->add('telephone', TextType::class, [
                    'label' => 'Numéro de téléphone',
                    'help' => 'Facultatif',
                    'attr' => [
                        'placeholder' => 'Ex: 06 980 80 90',
                    ],
                    'required' => false
                ])->add('localisation', TextType::class, [
                    'label' => "Adresse de l'entreprise",
                    'attr' => [
                        'placeholder' => "Adresse de l'entreprise",
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])->add('nomResponsable', TextType::class, [
                    'label' => "Nom du responsable",
                    'attr' => [
                        'placeholder' => "Nom du responsable",
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])->add('siteWeb', TextType::class, [
                    'label' => "Site web",
                    'attr' => [
                        'placeholder' => "Site web",
                        'class' => 'focus'
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]);
        } else {
            $builder
                ->add('logoFile', VichImageType::class, [
                    'label' => 'logo',
                    'required'  =>  false,
                    'allow_delete' =>  false,
                    'download_label'     =>  false,
                    'image_uri'     =>  false,
                    'download_uri'     =>  false,
                    'imagine_pattern'   =>  'largeavatar',
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])
                ->add('photoFile', VichImageType::class, [
                    'label' => 'photo',
                    'required'  =>  false,
                    'allow_delete' =>  false,
                    'download_label'     =>  false,
                    'image_uri'     =>  false,
                    'download_uri'     =>  false,
                    'imagine_pattern'   =>  'largeavatar',
                    'required' => false
                ])
                ->add('niu', TextType::class, [
                    'label' => "Numéro d'identification unique (NIU)",
                    'attr' => [
                        'placeholder' => "Numéro d'identification unique (NIU)",
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])->add('societe', TextType::class, [
                    'label' => "Nom de la société",
                    'attr' => [
                        'placeholder' => "Nom de la société",
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])
                ->add('services', EntityType::class, [
                    'label' => "Services que vous souhaitez offrir",
                    'class' => Service::class,
                    'multiple' => true,
                    'required' => false,
                    'by_reference' => false,
                    'query_builder' => function (EntityRepository $se) {
                        return $se->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                    },
                    'attr' => [
                        'class' => 'js-select2-services'
                    ],
                ])
                ->add('realisations', CollectionType::class, [
                    'label' => "Galeries : Les réalisations",
                    'entry_type' => RealisationType::class,
                    'by_reference' => false,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'required' => false
                ])
                ->add('villes', EntityType::class, [
                    'label' => "Périmètre (Dans quelles villes)",
                    'class' => Ville::class,
                    'multiple' => true,
                    'required' => false,
                    'by_reference' => false,
                    'query_builder' => function (EntityRepository $vil) {
                        return $vil->createQueryBuilder('v')->orderBy('v.name', 'ASC');
                    },
                    'attr' => [
                        'class' => 'js-select2-villes'
                    ],
                ])
                ->add('telephone', TextType::class, [
                    'label' => 'Numéro de téléphone',
                    'help' => 'Facultatif',
                    'attr' => [
                        'placeholder' => 'Ex: 06 980 80 90',
                    ],
                    'required' => false
                ])
                ->add('localisation', TextType::class, [
                    'label' => "Adresse de l'entreprise",
                    'attr' => [
                        'placeholder' => "Adresse de l'entreprise",
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])
                ->add('nomResponsable', TextType::class, [
                    'label' => "Nom du responsable",
                    'attr' => [
                        'placeholder' => "Nom du responsable",
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])
                ->add('siteWeb', TextType::class, [
                    'label' => "Site web",
                    'attr' => [
                        'placeholder' => "Site web",
                        'class' => 'focus'
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ])
                ->add('apropo', TextareaType::class, [
                    'label' => "A propos (Facultatif)",
                    'attr' => [
                        'placeholder' => 'Présentez votre entreprise en quelques mots',
                        'class' => 'mce'
                    ],
                    'required' => false
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'step' => []
        ]);
    }
}

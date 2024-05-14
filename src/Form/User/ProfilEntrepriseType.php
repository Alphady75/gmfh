<?php

namespace App\Form\User;

use App\Entity\SecteursActivite;
use App\Entity\Service;
use App\Entity\SousSecteursActivite;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfilEntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $step = $options['step'];

        $builder
            ->add('logoFile', VichImageType::class, [
                'label' => 'logo',
                'required'  =>  false,
                'allow_delete' =>  false,
                'download_label'     =>  false,
                'image_uri'     =>  false,
                'download_uri'     =>  false,
                'imagine_pattern'   =>  'largeavatar',
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
                    'class' => 'js-select2-villes location'
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Ex: 06 980 80 90',
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('localisation', TextType::class, [
                'label' => "Adresse de l'entreprise",
                'attr' => [
                    'placeholder' => "Adresse de l'entreprise",
                    'class' => 'focus location'
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
                ],
                'required' => false
            ])
            ->add('apropo', TextareaType::class, [
                'label' => "A propos (Facultatif)",
                'attr' => [
                    'placeholder' => 'Présentez votre entreprise en quelques mots',
                    'class' => 'textarea-style-1'
                ],
                'required' => false
            ])
            ->add('secteuractivite', EntityType::class, [
                'label' => "Secteur d'activité",
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
            ->add('facebook', TextType::class, [
                'label' => 'facebook',
                'attr' => [
                    'placeholder' => 'https://www.facebook.com/',
                ],
                'required' => false
            ])
            ->add('twitter', TextType::class, [
                'label' => 'twitter',
                'attr' => [
                    'placeholder' => 'https://www.twitter.com/',
                ],
                'required' => false
            ])
            ->add('linkedin', TextType::class, [
                'label' => 'linkedin',
                'attr' => [
                    'placeholder' => 'https://www.linkedin.com/',
                ],
                'required' => false
            ])
            ->add('whatsapp', TextType::class, [
                'label' => 'whatsapp',
                'attr' => [
                    'placeholder' => 'https://www.whatsapp.com/',
                ],
                'required' => false
            ])
            ->add('instagram', TextType::class, [
                'label' => 'instagram',
                'attr' => [
                    'placeholder' => 'https://www.instagram.com/',
                ],
                'required' => false
            ])
            ->add('pinterest', TextType::class, [
                'label' => 'pinterest',
                'attr' => [
                    'placeholder' => 'https://www.pinterest.com/',
                ],
                'required' => false
            ])
            ->add('tumblr', TextType::class, [
                'label' => 'tumblr',
                'attr' => [
                    'placeholder' => 'https://www.tumblr.com/',
                ],
                'required' => false
            ])
            ->add('youtube', TextType::class, [
                'label' => 'youtube',
                'attr' => [
                    'placeholder' => 'https://www.youtube.com/',
                ],
                'required' => false
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
                $soussecteuractivite = $data->getSoussecteuractivite(); //dd($soussecteuractivite);

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
            'data_class' => User::class,
            'step' => []
        ]);
    }
}

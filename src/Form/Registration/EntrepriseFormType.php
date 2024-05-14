<?php

namespace App\Form\Registration;

use App\Entity\SecteursActivite;
use App\Entity\SousSecteursActivite;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EntrepriseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom(s)',
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prénom(s)',
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
            ])->add('siege', TextType::class, [
                'label' => "Siège",
                'attr' => [
                    'placeholder' => "Siège",
                ],
                'constraints' => [
                    new NotBlank(),
                ]
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
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email (Ex: email@gmail.com)'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Mot de passe',],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password', 'placeholder' => "Répéter le mot de passe", 'class' => 'focus'],
                    'label' => 'Répéter le mot de passe',
                ],
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
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
        ]);
    }
}

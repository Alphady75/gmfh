<?php

namespace App\Form\Base;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\UX\Dropzone\Form\DropzoneType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fichierFile', DropzoneType::class, [
                "label" => false,
                'attr' => [
                    'placeholder' => "Glissez deposez ou cliquez pour sélectionner vos fichiers",
                    'class' => "radius",
                ],
                'required' => false
            ])
            ->add('contenu', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Ecrivez votre message', 'class' => 'text-muted border radius bg-light w-100 focus'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ecrivez votre message'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}

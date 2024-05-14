<?php

namespace App\Form\User;

use App\Entity\Realisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RealisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Votre réalisation...'],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => "Joindre une image",
                'help' => "Facultatif",
                'required' => false,
                'allow_delete' =>  false,
                'download_label'     =>  false,
                'image_uri'     =>  false,
                'download_uri'     =>  false,
                'imagine_pattern'   =>  'mediumavatar',
                'attr' => ['class' => ''],
            ])
            ->add('lien', TextType::class, [
                'label' => "Lien",
                'attr' => ['placeholder' => 'Lien...'],
                'help' => "Facultatif",
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'help' => 'Facultatif',
                'attr' => ['placeholder' => 'Description...'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Realisation::class,
        ]);
    }
}

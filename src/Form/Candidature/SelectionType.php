<?php

namespace App\Form\Candidature;

use App\Entity\Candidature;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SelectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('selectionMessage', TextareaType::class, [
            'label' => "Pourquoi selectionnez-vous cette candidature ?",
            'attr' => [
                'placeholder' => 'Pourquoi selectionnez-vous cette candidature ?',
                'class' => "focus textarea-style-1"
            ],
            'constraints'  =>  [
                new NotBlank()
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidature::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\MedicalHistory;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicalHistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('patient', EntityType::class, [
                'class'=> Patient::class,
                'choice_label' => 'firstName',
                'placeholder' => 'Prénom'
            ])
            ->add('allergies')
            ->add('other')
            ->add('treatment')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MedicalHistory::class,
        ]);
    }
}

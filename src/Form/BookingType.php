<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Patient;
use App\Entity\User;
use function Sodium\add;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('doctor', EntityType::class, [
                'class'=> User::class,
                'choice_label' => 'FullName',
                'placeholder' => 'Nom et Prénom'
            ])
            ->add('patient', EntityType::class, [
                'class'=> Patient::class,
                'choice_label' => 'FullName',
                'placeholder' => 'Nom et Prénom'
            ])
            ->add('beginAt')
            ->add('endAt')
            ->add('title')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}

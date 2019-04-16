<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstName')
            ->add('lastName')
            ->add('birthAt', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('gender', ChoiceType::class, array('choices' => array('Féminin' => 'Féminin','Masculin' => 'Masculin'),
                    'required' => false,))
            ->add('nir')
            ->add('email')
            ->add('phone')
            ->add('address')
            ->add('postcode')
            ->add('city')
	        ->add('DOCTOR', EntityType::class, [
		        'class'=> User::class,
		        'choice_label' => 'FullName',
		        'placeholder' => 'Nom et Prénom'
	        ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }


}

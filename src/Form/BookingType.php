<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Patient;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

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
//            ->add('patient', EntityType::class, [
//                'class'=> Patient::class,
//                'choice_label' => 'FullName',
//                'placeholder' => 'Nom et Prénom'
//            ])
	        ->add('patient', Select2EntityType::class, array(
		        'multiple' => false,
		        'remote_route'=>'patient_search',
		        'class'=> Patient::class,
		        'primary_key' => 'id',
		        'text_property' => 'FullName',
		        'minimum_input_length' => 2,
		        'page_limit' => 10,
		        'allow_clear' => true,
		        'delay' => 250,
		        'cache' => true,
		        'cache_timeout' => 60000, // if 'cache' is true
		        'allow_add' => [
			        'enabled' => true,
			        'new_tag_text' => ' (NEW)',
			        'new_tag_prefix' => '__',
			        'tag_separators' => '[",", " "]'
		        ],
	        ))
            ->add('beginAt',  DateTimeType::class)
            ->add('endAt',  DateTimeType::class)
            ->add('title', TextType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}

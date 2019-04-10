<?php

namespace App\Form;

use App\Entity\Actor;
use App\Entity\Consultation;
use App\Entity\Drug;
use App\Entity\MedicalHistory;
use App\Entity\Patient;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
		        'text_property' => 'firstName',
		        'minimum_input_length' => 2,
		        'page_limit' => 10,
		        'allow_clear' => true,
		        'delay' => 250,
		        'cache' => true,
		        'cache_timeout' => 60000, // if 'cache' is true

	        ))


            ->add('name')
            ->add('diagnostic')

            ->add('medicament', Select2EntityType::class, array(
		        'multiple' => true,
		        'remote_route'=>'drug_search',
		        'class'=> Drug::class,
		        'primary_key' => 'id',
		        'text_property' => 'name',
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
            ->add('treatment')
            ->add('decision')
            ->add('allergies')
            ->add('medFamilyHistory')
            ->add('medTreatmentHistory')
            ->add('others')


// Ajouter le formulaire des MedHistory
//            ->add('medHistory', MedicalHistoryType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Drug;
use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

	        ->add('patient', Select2EntityType::class, array(
		        'required' =>  true,
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
		        'cache_timeout' => 60000,
	        ))

            ->add('name', TextType::class, [
	            'required' =>  true,
	            'label'   => false,
	            'attr'    => [
		            'placeholder' => 'Motif de la visite'
	            ]
            ])
            ->add('diagnostic', TextType::class, [
	            'required' =>  false,
	            'label'   => false,
            ])

            ->add('medicament', Select2EntityType::class, array(
		        'multiple' => true,
		        'remote_route'=>'drug_search',
		        'class'=> Drug::class,
		        'primary_key' => 'id',
		        'text_property' => 'denomination',
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
            ->add('treatment', TextType::class, [
	            'required' =>  false,
	            'label'   => false,
            ])
            ->add('decision', TextType::class, [
	            'required' =>  false,
	            'label'   => false,
            ])
            ->add('allergies', TextType::class, [
	            'required' =>  false,
	            'label'   => false,
            ])
            ->add('medFamilyHistory', TextType::class, [
	            'required' =>  false,
	            'label'   => false,
            ])
            ->add('medTreatmentHistory', TextType::class, [
	            'required' =>  false,
	            'label'   => false,
            ])
            ->add('others', TextType::class, [
	            'required' =>  false,
	            'label'   => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}

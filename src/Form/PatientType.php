<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PatientType extends AbstractType {
	public function buildForm( FormBuilderInterface $builder, array $options ):Void {
		$builder
			->add( 'DOCTOR', TextType::class)
			->add('lastName', TextType::class, [
				'required' =>  true,
				'label'   => false,
				'attr'    => [
					'placeholder' => 'Nom'
				]
			])
			->add('firstName', TextType::class, [
				'required' =>  true,
				'label'   => false,
				'attr'    => [
					'placeholder' => 'Prénom'
				]
			])
			->add( 'birthAt', BirthdayType::class, [
				'widget' => 'single_text',
				'format' => 'yyyy-MM-dd',
			] )
			->add( 'gender', ChoiceType::class, array(
				'choices'  => array( 'Féminin' => 'Féminin', 'Masculin' => 'Masculin' ),
				'required' => true,
				'placeholder'   => 'Genre'
			) )
			->add( 'nir', IntegerType::class, [
				'required' =>  false,
				'label'   => false,
			])
			->add( 'email', EmailType::class, [
				'required' =>  false,
				'label'   => false,
			])
			->add( 'phone' , IntegerType::class, [
				'required' =>  false,
				'label'   => false,
			])
			->add( 'address', TextType::class, [
				'required' =>  true,
				'label'   => false,
			])
			->add( 'postcode', IntegerType::class, [
				'required' =>  true,
				'label'   => false,
			])
			->add( 'city' , TextType::class, [
				'required' =>  true,
				'label'   => false,
			])
		;
	}

	public function configureOptions( OptionsResolver $resolver ):void {
		$resolver->setDefaults( [
			'data_class' => Patient::class,
		] );
	}



//->add( 'DOCTOR'	, EntityType::class, array(
//				'class'         => User::class,
//				'choice_label'  => 'FullName',
//				'placeholder'   => 'Nom et Prénom *',
//				'query_builder' => static function ( EntityRepository $er ) {
//					$role = 'ROLE_DOCTOR';
//
//					return $er->createQueryBuilder( 'u' )
//					          ->orderBy( 'u.firstName', 'ASC' )
//					          ->andWhere( 'u.roles LIKE :role' )
//					          ->setParameter( 'role', '%' . $role . '%' );
//				},
//			) )

}

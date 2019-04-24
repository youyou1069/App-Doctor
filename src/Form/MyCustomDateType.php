<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MyCustomDateType extends DateType
{
	public function configureOptions(OptionsResolver $resolver)
	{
		// Set the defaults from the DateTimeType we're extending from
		parent::configureOptions($resolver);

		// Override: Go back 120 years and add 0 years
		$resolver->setDefault('years', range(date('Y') - 120, date('Y') +0));


	}
}
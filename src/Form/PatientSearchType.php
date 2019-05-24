<?php


namespace App\Form;

use App\Entity\PatientSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientSearchType extends  AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('lastName', TextType::class, [
                'required' =>  false,
                'label'   => false,
                'attr'    => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('firstName', TextType::class, [
                'required' =>  false,
                'label'   => false,
                'attr'    => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('postcode', IntegerType::class, [
                'required' =>  false,
                'label'   => false,
                'attr'    => [
                    'placeholder' => 'Code Postal'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PatientSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }


    //Créer une méthode pour alléger le contenu du URL
    /**
     * @return string|null
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
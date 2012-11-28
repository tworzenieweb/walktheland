<?php

namespace WNC\Bundle\OrganizerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DonateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(10, 18, 25, 100, 180, 250, 500, 1000);
        
        $labels = array_map(function($value) {
          return $value .= '$';
        }, $choices);
        
        $choices = array_combine($choices, $labels);
      
        $builder
            ->add('who', null, array(
                'label' => 'Donor name',
                'attr' => array(
                    'placeholder' => 'The name that will be displayed on dedicated page'
                )
            ))
            ->add('amount', 'choice', array(
                'label' => 'Amount',
                'choices' => $choices
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WNC\Bundle\OrganizerBundle\Entity\Donate'
        ));
    }

    public function getName()
    {
        return 'wnc_bundle_organizerbundle_donatetype';
    }
}

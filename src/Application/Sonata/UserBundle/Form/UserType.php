<?php

namespace Application\Sonata\UserBundle\Form;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use WNC\Bundle\OrganizerBundle\Form\OrganizationType;

class UserType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
            ->add('firstname', null)
            ->add('lastname')
             ->add('gender', 'choice', array(
                'multiple'     => false,
                'expanded'     => true,
                'choices'      => array('0' => 'male', '1' => 'female'),
                'widget_type'  => "inline"
            ))
            ->add('phone')
            ->add('organization', new OrganizationType($options));
      
        parent::buildForm($builder, $options);
      
        $builder->remove('username');
        
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\Sonata\UserBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'application_sonata_user_registration';
    }
}

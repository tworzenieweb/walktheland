<?php

namespace WNC\Bundle\OrganizerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganizationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('community')
            ->add('state')
            ->add('event_location')
            ->add('event_time')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WNC\Bundle\OrganizerBundle\Entity\Organization'
        ));
    }

    public function getName()
    {
        return 'wnc_bundle_organizerbundle_organizationtype';
    }
}

<?php

namespace WNC\Bundle\OrganizerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use WNC\Bundle\OrganizerBundle\Form\DataTransformer\OrganizationToIdTransformer;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $entityManager = $options['em'];
        $transformer = new OrganizationToIdTransformer($entityManager);

        // add a normal text field, but add your transformer to it
        $org =
            $builder->create('organization', 'hidden')
                ->addModelTransformer($transformer);
        
        
        $builder
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add($org)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WNC\Bundle\OrganizerBundle\Entity\Participant'
        ));
        
        $resolver->setRequired(array(
            'em',
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    public function getName()
    {
        return 'wnc_bundle_organizerbundle_participanttype';
    }
    
}

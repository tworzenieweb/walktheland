<?php

namespace WNC\Bundle\OrganizerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;



class ParticipantAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('email')
            ->add('created')
            ->add('organization')
            
        ;
        
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('firstName')
            ->add('lastName')
            ->add('phone')
            ->add('email')
            ->add('created')
            ->add('organization')
        ;
    }
}
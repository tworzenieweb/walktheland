<?php

namespace WNC\Bundle\OrganizerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;



class OrganizationAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('event_location')
            ->add('event_time')
            ->add('state')
            ->add('community')
            ->add('contact', 'sonata_type_admin', array('delete' => false))
            
        ;
        
        $formMapper->get('contact')->remove('groups')->remove('username');
        
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('event_location')
            ->add('event_time')
            ->add('state')
            ->add('community')
            ->add('contact')
        ;
    }
}
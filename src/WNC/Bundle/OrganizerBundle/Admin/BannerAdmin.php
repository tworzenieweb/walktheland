<?php

namespace WNC\Bundle\OrganizerBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class BannerAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('link')
            ->add('enabled')
            ->add('type', 'choice', array(
                'choices' => array(
                    'banner' => 'banner',
                    'sidebar' => 'sidebar'
                )
            ))
            ->add('picture', 'sonata_type_model_list', array(), array('link_parameters' => array('context' => 'advertisment')))
        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('picture', 'string', array('template' => 'WNCOrganizerBundle:Admin:list_image.html.twig'))
            ->add('type')
            ->addIdentifier('link')
            ->add('enabled')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }
    
    protected function configureDatagridFilters(DatagridMapper $datagrid)
    {
        $datagrid
            ->add('type', 'doctrine_orm_choice', array(
                'field_type' => 'choice',
                'field_options' => array(
                'choices' => array(
                    'banner' => 'banner',
                    'sidebar' => 'sidebar'
                ))
            ))
            ->add('enabled')
        ;
    }
}
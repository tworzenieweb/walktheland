<?php


namespace Application\Sonata\UserBundle\Admin;

use Sonata\UserBundle\Admin\Entity\UserAdmin as BaseAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class UserAdmin extends BaseAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('first_name');
        $listMapper->add('last_name');
      
        parent::configureListFields($listMapper);
      
        $listMapper
            ->add('isConfirmed', 'boolean')
            ->remove('groups')
            ->remove('impersonating');
        
        $listMapper->add('_action', 'actions', array(
            'actions' => array(
                'view' => array(),
                'edit' => array(),
            )
        ));
    }
    
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
                ->add('first_name')
                ->add('last_name')
                ->add('username')
                ->add('email')
            ->end()
            ->with('Organization')
                ->add('organization.name')
                ->add('organization.event_location')
                ->add('organization.event_time')
                ->add('organization.comunity')
                ->add('organization.state')
            ->end()
            
        ;
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('first_name', 'text')
                ->add('last_name', 'text')
                ->add('username', 'text')
                ->add('email', 'text')
                ->add('plainPassword', 'text', array('required' => false))
            ->end()
            ->with('Groups')
                ->add('groups', 'sonata_type_model', array('required' => false, 'expanded' => true, 'multiple' => true))
            ->end()
        ;

        
    }

}

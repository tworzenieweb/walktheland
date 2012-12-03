<?php
namespace WNC\Bundle\OrganizerBundle\Navbar;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;

/**
 * An example howto inject a default KnpMenu to the Navbar
 * see also Resources/config/example_menu.yml
 * and example_navbar.yml
 * @author phiamo
 *
 */
class MenuBuilder extends AbstractNavbarMenuBuilder
{
  
    protected $securityContext;
    protected $isLoggedIn;

    public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext)
    {
        parent::__construct($factory);

        $this->securityContext = $securityContext;
        $this->isLoggedIn = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function createMainMenu(Request $request)
    {

      
        $menu = $this->createNavbarMenuItem();
        $menu->addChild('Homepage', array('route' => 'homepage'));
        $menu->addChild('Participating Communities', array('route' => 'organizations_list'));
        
        if(!$this->securityContext->isGranted('ROLE_USER')) {
          $menu->addChild('Sign Up', array('route' => 'fos_user_registration_register'));
        }
        $menu->addChild('Contact', array('route' => 'contact'));
        $menu->addChild('Donate', array('route' => 'donate'));

        $menu->setCurrentUri($request->getRequestUri());
        
        return $menu;
    }
    
    public function createRightMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

//        print_r($this->securityContext->getToken()); exit;
        
        if($this->securityContext->isGranted('ROLE_USER')) {
          $menu->addChild(sprintf('My Organization (%s)', $this->securityContext->getToken()->getUser()), array('route' => 'sonata_user_profile_show'));
          $menu->addChild('Logout', array('route' => 'fos_user_security_logout'));
        }
        else
          $menu->addChild('Login', array('route' => 'fos_user_security_login'));
        
        $menu->setCurrentUri($request->getRequestUri());
        
        return $menu;
    }

   
}
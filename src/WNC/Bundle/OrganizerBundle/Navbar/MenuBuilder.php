<?php
namespace WNC\Bundle\OrganizerBundle\Navbar;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;
use Doctrine\ORM\EntityManager;

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
    protected $em;

    public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext, EntityManager $em)
    {
        parent::__construct($factory);

        $this->em = $em;
        $this->securityContext = $securityContext;
        $this->isLoggedIn = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function createMainMenu(Request $request)
    {

      
        $menu = $this->createNavbarMenuItem();
        $menu->addChild('HOME', array('route' => 'homepage'));
        $menu->addChild('PARTICIPATING COMMUNITIES', array('route' => 'organizations_list'));
        
        $results = $this->em->getRepository('WNCCMSBundle:Page')->findBy(array('in_menu' => true));
        foreach($results as $result)
        {
          $menu->addChild(strtoupper($result->getTitle()), array('route' => 'wnc_cms_page_show', 'routeParameters' => array('slug' => $result->getSlug())));
        }
        

        $menu->setCurrentUri($request->getRequestUri());
        
        return $menu;
    }
    
    public function createRightMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav pull-right');

        
        $menu->addChild('CONTACT', array('route' => 'contact'));
        $menu->addChild('DONATE', array('route' => 'donate'));
        
        
        
//        if($this->isLoggedIn) {
//          $menu->addChild(sprintf('My Organization (%s)', $this->securityContext->getToken()->getUser()), array('route' => 'sonata_user_profile_show'));
//          $menu->addChild('LOGOUT', array('route' => 'fos_user_security_logout'));
//        }
//        else
//          $menu->addChild('LOGIN', array('route' => 'fos_user_security_login'));
        
        $menu->setCurrentUri($request->getRequestUri());
        
        return $menu;
    }

   
}
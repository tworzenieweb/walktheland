<?php

namespace WNC\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
    
    
    /**
     * @Route("/page/{slug}", name="wnc_cms_page_show")
     * @Template()
     */
    public function pageAction($slug)
    {
      
      return array(
          
          'page' => $this->getDoctrine()
              ->getRepository('WNCCMSBundle:Page')
              ->findOneBySlug($slug)
          
      );
    
    }
    
    /**
     * @Route("/article/{slug}", name="article_show")
     * @Template()
     */
    public function showAction($slug)
    {
    
      return array(
          
          'article' => $this->getDoctrine()
              ->getRepository('WNCCMSBundle:Article')
              ->findOneBySlug($slug)
          
      );
      
    }
    
    /**
     * @Template()
     * @Cache(expires="+1 years")
     */
    public function articlesAction()
    {
    
        return array(
    
            'articles' => $this->getDoctrine()->getRepository('WNCCMSBundle:Article')->findAllWithMedias()
            
        );
            
    
    }
}

<?php

namespace WNC\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @Route("/article/{slug}", name="article_show")
     * @Template()
     */
    public function show($slug)
    {
    
    }
    
    /**
     * @Template()
     */
    public function articlesAction()
    {
    
        return array(
    
            'articles' => $this->getDoctrine()->getRepository('WNCCMSBundle:Article')->findAll()
            
        );
            
    
    }
}

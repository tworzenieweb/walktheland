<?php

namespace WNC\Bundle\OrganizerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use WNC\Bundle\OrganizerBundle\Entity\Participant;
use WNC\Bundle\OrganizerBundle\Form\ParticipantType;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction()
    {
        return array('name' => 1);
    }
    
    /**
     * @Route("/greeting-card", name="greeting_card")
     * @Template()
     */
    public function greetingCardAction()
    {
      return array('donators' => 
          $this->getDoctrine()->getRepository('WNCOrganizerBundle:Donate')->getDonators()
              );
      
    }

    /**
     * @Route("/register/{slug}", name="register_walk")
     * @Template()
     */
    public function registerWalkAction($slug)
    {
      $organization = $this->getDoctrine()
              ->getRepository('WNCOrganizerBundle:Organization')
              ->findOneBySlugWithContact($slug);
      
      $entity = new Participant();
      $entity->setOrganization($organization);
      $em = $this->getDoctrine()->getEntityManager();
      
      $form = $this->createForm(new ParticipantType(), $entity, array(
        'em' => $this->getDoctrine()->getEntityManager(),
            ));
      
      if ('POST' === $this->getRequest()->getMethod()) {

          
            $form->bindRequest($this->getRequest());
            
            if($form->isValid()) {
              $em->persist($entity);
              $em->flush( $entity);
              
              $this->get('session')->setFlash('success',"Organizator will be informed about your registration");
              
              return new RedirectResponse($this->generateUrl('organization_show', array(
                  'slug' => $organization->getSlug()
              )));
              
              
            }
        
      }
      
      return array(
          'organization' => $organization,
          'form' => $form->createView()
      );
      
    }
    
    /**
     * @Route("/banner", name="banner")
     * @Template()
     */
    public function bannerAction()
    {
      
        $gallery = $this->get('sonata.media.manager.gallery')->findOneBy(array(
            'id'      => 2,
            'enabled' => true
        ));

        if (!$gallery) {
            throw new NotFoundHttpException('unable to find the gallery with the id');
        }

        return array(
            'gallery'   => $gallery,
        );
      
    }
    
    /**
     * 
     * @Route("/share/{slug}", name="share_community")
     * @Template()
     */
    public function shareAction($slug)
    {
        
    }
    
    /**
     * 
     * @Route("/advertisment", name="advertisment")
     * @Template()
     */
    public function advertismentAction()
    {
        $gallery = $this->get('sonata.media.manager.gallery')->findOneBy(array(
            'id'      => 1,
            'enabled' => true
        ));

        if (!$gallery) {
            throw new NotFoundHttpException('unable to find the gallery with the id');
        }

        return array(
            'gallery'   => $gallery,
        );
    }
    
    /**
     * @Route("/organizations", name="organizations_list")
     * @Template()
     */
    public function listAction()
    {
      return array('organizations' => 
          $this->getDoctrine()->getRepository('WNCOrganizerBundle:Organization')->findAllWithContact());
    }
    
    /**
     * @Route("/organization/{slug}", name="organization_show")
     * @Template()
     */
    public function showAction($slug)
    {
      return array('organization' => 
          $this->getDoctrine()->getRepository('WNCOrganizerBundle:Organization')->findOneBySlugWithContact($slug));
    }
}

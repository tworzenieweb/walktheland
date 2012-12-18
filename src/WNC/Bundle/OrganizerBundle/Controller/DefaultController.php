<?php

namespace WNC\Bundle\OrganizerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use WNC\Bundle\OrganizerBundle\Entity\Participant;
use WNC\Bundle\OrganizerBundle\Form\ParticipantType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     * @Cache(expires="tomorrow")
     */
    public function indexAction()
    {
        return array(); 
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
     * @Route("/register-walk/{slug}", name="register_walk")
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
              
              $this->get('fos_user.mailer.twig_swift')->sendOrganizationNotification($organization, $entity);
              
              
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
     * 
     * @Route("/advertisment/{type}", name="advertisment")
     * @Template()
     * @Cache(expires="tomorrow")
     */
    public function advertismentAction($type)
    {
      return $this->render('WNCOrganizerBundle:Default:advertisment_' . $type . '.html.twig', array(
        'advertisments' => $this->getDoctrine()->getRepository('WNCOrganizerBundle:Banner')->getEnabledWithPhoto($type)
      ));
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
     * @Route("/organization/participants", name="organization_participants")
     * @Secure(roles="ROLE_USER")
     * @Template()
     */
    public function participantsAction()
    {
      return array('participants' => $this->getUser()->getOrganization()->getParticipants());
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

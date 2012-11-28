<?php

namespace WNC\Bundle\OrganizerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use WNC\Bundle\OrganizerBundle\Form\ContactType;


class ContactController extends Controller
{
    
    
        
    /**
     * @Route("/contact", name="contact")
     * @template
     */
    public function contactAction($contentDocument = null)
    {
        $form   = $this->createForm(new ContactType());
        
        $request = $this->getRequest();
        
        if($request->getMethod() == 'POST')
        {
            $form->bindRequest($request);
            
            if($form->isValid())
            {
                $values = $form->getData();
                
                /* @var $message Swift_Mime_Message */
                $message = \Swift_Message::newInstance()
                    ->setSubject($values['topic'])
                    ->setFrom(array($values['email'] => $values['name']))
                    ->setReplyTo(array($values['email'] => $values['name']))
                    ->setTo($this->container->getParameter('contact_email'))
                    ->setContentType("text/html")
                    ->setBody($this->renderView('WNCOrganizerBundle:Contact:mail.html.twig', $values));
                
                $this->get('mailer')->send($message);                
                $this->get('session')->setFlash('success',"Your message has been sent");
                
                return $this->redirect($this->generateUrl('contact'));
                
            }
        }
        
//        if (!$contentDocument) {
//            throw new NotFoundHttpException('Content not found');
//        }
        
        return array(
            'form' => $form->createView(),
            'contentDocument' => $contentDocument
        );
        
        
        
    }
}

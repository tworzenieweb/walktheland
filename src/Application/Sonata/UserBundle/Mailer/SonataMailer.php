<?php

namespace Application\Sonata\UserBundle\Mailer;

use FOS\UserBundle\Mailer\TwigSwiftMailer;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WNC\Bundle\OrganizerBundle\Entity\Organization;
use WNC\Bundle\OrganizerBundle\Entity\Participant;

class SonataMailer extends TwigSwiftMailer
{
  public function sendOrganizationNotification(Organization $organization, Participant $participant)
  {
    
    $template = 'ApplicationSonataUserBundle:messages:organization.html.twig';
    $url = $this->router->generate('organization_participants', array(), true);
    $context = array(
        'organization' => $organization,
        'participant' => $participant,
        'url' => $url
    );
    
    $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $organization->getContact()->getEmail());
    
  }
  
  public function sendEnabledMessage(UserInterface $user)
  {
    
        $template = 'ApplicationSonataUserBundle:messages:enabled.html.twig';
        $url = $this->router->generate('fos_user_security_login', array(), true);
        $context = array(
            'user' => $user
        );
        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], $user->getEmail());
    
  }
  
}
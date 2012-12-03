<?php

namespace WNC\Bundle\OrganizerBundle\Listener;


use Doctrine\ORM\EntityManager;

use JMS\Payment\CoreBundle\PluginController\Event\PaymentStateChangeEvent;
use JMS\Payment\CoreBundle\Model\PaymentInterface;

use Symfony\Component\HttpKernel\Log\LoggerInterface;

class PaymentListener
{
    /**
     * @var EntityManager
     */
    private $em;
    
    private $logger;


    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * @param PaymentStateChangeEvent $event
     */
    public function onPaymentStateChange(PaymentStateChangeEvent $event)
    {
        
      $this->logger->debug(sprintf('Payment status changed from %s to %s: ', $event->getOldState(), $event->getNewState()));
      
        switch ($event->getNewState()) {
            case PaymentInterface::STATE_DEPOSITED: {
                $donate = $this->em->getRepository('WNCOrganizerBundle:Donate')->findOneByPaymentInstruction($event->getPaymentInstruction());

                $donate->setConfirmed(true);
                $this->em->persist($donate);

  
            } break;
        }
    }
}
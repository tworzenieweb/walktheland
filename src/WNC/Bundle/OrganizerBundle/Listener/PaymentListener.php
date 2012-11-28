<?php

namespace WNC\Bundle\OrganizerBundle\Listener;


use Doctrine\ORM\EntityManager;

use JMS\Payment\CoreBundle\PluginController\Event\PaymentStateChangeEvent;
use JMS\Payment\CoreBundle\Model\PaymentInterface;



class PaymentListener
{
    /**
     * @var EntityManager
     */
    private $em;


    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param PaymentStateChangeEvent $event
     */
    public function onPaymentStateChange(PaymentStateChangeEvent $event)
    {
        switch ($event->getNewState()) {
            case PaymentInterface::STATE_APPROVED: {
                $donate = $this->em->getRepository('WNCOrganizerBundle:Order')->findOneByPaymentInstruction($event->getPaymentInstruction());

                $donate->setConfirmed(true);
                $this->em->flush($order);

  
            } break;
        }
    }
}
<?php

namespace WNC\Bundle\OrganizerBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;

use JMS\Payment\CoreBundle\PluginController\Result;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;


use WNC\Bundle\OrganizerBundle\Form\DonateType;
use WNC\Bundle\OrganizerBundle\Entity\Donate;

class PaymentController extends Controller
{
    /** @DI\Inject */
    private $request;
 
    /** @DI\Inject */
    private $router;
 
    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;
 
    /** @DI\Inject("payment.plugin_controller") */
    private $ppc;
 
    
    /**
     * @Route("/donate", name="donate")
     * @Template()
     */
    public function donateAction()
    {
      $entity = new Donate();
      
      $form = $this->createForm(new DonateType(), $entity);
      
      if ('POST' === $this->request->getMethod()) {

          
            $form->bindRequest($this->request);
            
            if($form->isValid()) {
              $this->em->persist($entity);
              $this->em->flush( $entity);
              
              return new RedirectResponse($this->generateUrl('payment', array(
                  'id' => $entity->getId()
              )));
              
              
            }
        
      }
      
      return array('form' => $form->createView());
    }
    
    /**
     * @Route("/pay/{id}", name="payment")
     * @Template()
     */
    public function paymentAction($id) { // this is a personnal ID i pass to the controler to identify the previous shopping cart

      $order = $this->em->getRepository('WNCOrganizerBundle:Donate')->find($id);

      $form = $this->getFormFactory()->create('jms_choose_payment_method', null, array(
          'amount' => $order->getAmount(),
          'currency' => 'USD',
          'default_method' => 'payment_paypal', // Optional
          'predefined_data' => array(
              'paypal_express_checkout' => array(
                  'return_url' => $this->router->generate('payment_complete', array(
                      'id' => $order->getId()
                          ), true),
                  'cancel_url' => $this->router->generate('payment_cancel', array(
                      'id' => $order->getId()
                          ), true)
              ),
          ),
      ));

      if ('POST' === $this->request->getMethod()) {
        $form->bindRequest($this->request);


        if ($form->isValid()) {
          $instruction = $form->getData();
          $this->ppc->createPaymentInstruction($instruction);
          $order->setPaymentInstruction($instruction);
          $this->em->persist($order);
          $this->em->flush($order);
          
          
          return new RedirectResponse($this->router->generate('payment_complete', array(
                    'id' => $order->getId(),
          )));
          
        }
      }
      return $this->render('WNCOrganizerBundle:Payment:pay.html.twig', array('id' => $id, 'form' => $form->createView()));
    }
    
    /** @DI\LookupMethod("form.factory") */
    protected function getFormFactory() { }
    
    
    
    /**
     * @Route("/payment/complete/{id}", name="payment_complete")
     * @Template()
     */
    public function paymentCompleteAction($id) {
      
        $order = $this->em->getRepository('WNCOrganizerBundle:Donate')->find($id);

        $instruction = $order->getPaymentInstruction();
        if (null === $pendingTransaction = $instruction->getPendingTransaction()) {
            $payment = $this->ppc->createPayment($instruction->getId(), $instruction->getAmount() - $instruction->getDepositedAmount());
        } else {
            $payment = $pendingTransaction->getPayment();
        }

        $result = $this->ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());
        if (Result::STATUS_PENDING === $result->getStatus()) {
            $ex = $result->getPluginException();

            if ($ex instanceof ActionRequiredException) {
                $action = $ex->getAction();

                if ($action instanceof VisitUrl) {
                    return new RedirectResponse($action->getUrl());
                }

                throw $ex;
            }
        } else if (Result::STATUS_SUCCESS !== $result->getStatus()) {
            throw new \RuntimeException('Transaction was not successful: '.$result->getReasonCode());
        }

      
    }

    /**
     * @Route("/payment/cancel/{id}", name="payment_cancel")
     * @Template()
     */
    public function paymentCancelAction($id) {
      
        $this->get('session')->getFlashBag()->add('info', 'Transaction canceled.');
        return $this->redirect($this->generateUrl('donate'));
      
    }
}
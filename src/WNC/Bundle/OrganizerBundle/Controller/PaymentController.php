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
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;
use JMS\Payment\CoreBundle\Entity\ExtendedData;

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
      
      // under real conditions, you'll likely not hard-code the payment amount,
      // currency, etc. here but retrieve it from a different source (like a form)
      $instruction = new PaymentInstruction($order->getAmount(), 'USD', 'paypal_express_checkout', new ExtendedData());
      $this->ppc->createPaymentInstruction($instruction);

      
      $order->setPaymentInstruction($instruction);
      
      $this->em->persist($order);
      
      // create the payment for the transaction (this allows you for example to
      // collect money for the same payment instruction in multiple payments).
      // In this case, we collect the entire amount in one payment.
      $paymentId = $this->ppc->createPayment($instruction->getId(), $order->getAmount())->getId();

      // Set the return/cancel Url
      $ext_data = $instruction->getExtendedData();
      $ext_data->set('return_url', $this->get('router')->generate('payment_complete', array('id' => $paymentId), true));
      $ext_data->set('cancel_url', $this->router->generate('payment_cancel', array(
                      'id' => $order->getId()
                          ), true));

      // Set the details of the Payment
      // How to set this Parameters: https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_WPCustomizing
      $instruction->getExtendedData()->set('checkout_params',  array('L_PAYMENTREQUEST_0_NAME0' => 'Walktheland Donation',
                                                              'L_PAYMENTREQUEST_0_NUMBER0' => $order->getId(),
                                                              'L_PAYMENTREQUEST_0_AMT0' => $order->getAmount(),
                                                              'L_PAYMENTREQUEST_0_QTY0' => '1',
                                                              'PAYMENTREQUEST_0_CURRENCYCODE' => 'USD'));
     
      $result = $this->ppc->approveAndDeposit($paymentId, $order->getAmount());
      
      if (Result::STATUS_PENDING === $result->getStatus()) {
            $ex = $result->getPluginException();

            if ($ex instanceof ActionRequiredException) {
                $action = $ex->getAction();

                // in this case we are redirect to Paypal
                if ($action instanceof VisitUrl) {
                    return $this->redirect($action->getUrl());
                }

                // no supported action
                throw $ex;
            }
        } else if (Result::STATUS_SUCCESS !== $result->getStatus()) {
            // you can do your error processing here

            // the reasoning code is set by the payment backend provider and indicates what
            // exactly went wrong during the transaction. all transactions are also logged to
            // the database, so you can check this at any time.
            throw new \RuntimeException('Transaction was not successful: '.$result->getReasonCode());
        }
      
    }
    
    /** @DI\LookupMethod("form.factory") */
    protected function getFormFactory() { }
    
    
    
    /**
     * @Route("/payment/complete/{id}", name="payment_complete")
     * @Template()
     */
    public function paymentCompleteAction($id) {
        
        $payment = $this->em->getRepository('JMSPaymentCoreBundle:Payment')->find($id);
        

        $result = $this->ppc->approveAndDeposit($id, $payment->getTargetAmount());
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
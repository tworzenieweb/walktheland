<?php

namespace WNC\Bundle\OrganizerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Donate
 *
 * @ORM\Table("wnc_donate")
 * @ORM\Entity(repositoryClass="WNC\Bundle\OrganizerBundle\Entity\DonateRepository")
 */
class Donate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="who", type="string", length=255)
     */
    private $who;

    /**
     * @var integer
     *c
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    
    /**  
     * @ORM\OneToOne(targetEntity="JMS\Payment\CoreBundle\Entity\PaymentInstruction")
     */
    private $paymentInstruction;

    
    /**
     * @ORM\Column(name="confirmed", type="boolean", nullable=false)
     * @var boolean
     */
    private $confirmed = false;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set who
     *
     * @param string $who
     * @return Donate
     */
    public function setWho($who)
    {
        $this->who = $who;
    
        return $this;
    }

    /**
     * Get who
     *
     * @return string 
     */
    public function getWho()
    {
        return $this->who;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return Donate
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     * @return Donate
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    
        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean 
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set paymentInstruction
     *
     * @param \JMS\Payment\CoreBundle\Entity\PaymentInstruction $paymentInstruction
     * @return Donate
     */
    public function setPaymentInstruction(\JMS\Payment\CoreBundle\Entity\PaymentInstruction $paymentInstruction = null)
    {
        $this->paymentInstruction = $paymentInstruction;
    
        return $this;
    }

    /**
     * Get paymentInstruction
     *
     * @return \JMS\Payment\CoreBundle\Entity\PaymentInstruction 
     */
    public function getPaymentInstruction()
    {
        return $this->paymentInstruction;
    }
}
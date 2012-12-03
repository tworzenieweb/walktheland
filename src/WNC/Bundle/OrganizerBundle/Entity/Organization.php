<?php

namespace WNC\Bundle\OrganizerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Organization
 *
 * @ORM\Table("wnc_organization")
 * @ORM\Entity(repositoryClass="WNC\Bundle\OrganizerBundle\Entity\OrganizerRepository")
 * 
 */
class Organization
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="event_location", type="string", length=255)
     */
    private $event_location;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="event_time", type="datetime")
     */
    private $event_time;
    
    /**
     * @ORM\OneToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="organization")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;
    
     /**
     * @Gedmo\Slug(fields={"community"})
     * @ORM\Column(length=64, unique=true)
     */
    private $slug;
    
    /**
     * @var string
     *
     * @ORM\Column(name="community", type="string", length=255)
     */
    private $community;
    
    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="organization")
     */
    private $participants;
    

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }
    


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
     * Set name
     *
     * @param string $name
     * @return Organization
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set event_location
     *
     * @param string $eventLocation
     * @return Organization
     */
    public function setEventLocation($eventLocation)
    {
        $this->event_location = $eventLocation;
    
        return $this;
    }

    /**
     * Get event_location
     *
     * @return string 
     */
    public function getEventLocation()
    {
        return $this->event_location;
    }

    /**
     * Set event_time
     *
     * @param \DateTime $eventTime
     * @return Organization
     */
    public function setEventTime($eventTime)
    {
        $this->event_time = $eventTime;
    
        return $this;
    }

    /**
     * Get event_time
     *
     * @return \DateTime 
     */
    public function getEventTime()
    {
        return $this->event_time;
    }

    /**
     * Set contact
     *
     * @param \Application\Sonata\UserBundle\Entity\User $contact
     * @return Organization
     */
    public function setContact(\Application\Sonata\UserBundle\Entity\User $contact = null)
    {
        $this->contact = $contact;
    
        return $this;
    }

    /**
     * Get contact
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Organization
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    public function __toString() {
      return $this->name;
    }

    /**
     * Set community
     *
     * @param string $community
     * @return Organization
     */
    public function setCommunity($community)
    {
        $this->community = $community;
    
        return $this;
    }

    /**
     * Get community
     *
     * @return string 
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Organization
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add participants
     *
     * @param \WNC\Bundle\OrganizerBundle\Entity\Participant $participants
     * @return Organization
     */
    public function addParticipant(\WNC\Bundle\OrganizerBundle\Entity\Participant $participants)
    {
        $this->participants[] = $participants;
    
        return $this;
    }

    /**
     * Remove participants
     *
     * @param \WNC\Bundle\OrganizerBundle\Entity\Participant $participants
     */
    public function removeParticipant(\WNC\Bundle\OrganizerBundle\Entity\Participant $participants)
    {
        $this->participants->removeElement($participants);
    }

    /**
     * Get participants
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getParticipants()
    {
        return $this->participants;
    }
}
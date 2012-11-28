<?php

namespace WNC\Bundle\OrganizerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Application\Sonata\UserBundle\Entity\User;
use WNC\Bundle\OrganizerBundle\Entity\Organization;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        
      
        $names = array('John', 'Andrew', 'Matthew', 'Anthony', 'Adam', 'Lois');
        $lastnames = array('Smith', 'Andrews', 'Kutcher', 'Kidley', 'Show', 'Doe');
      
        $csvLocation = __DIR__ . '/../../Resources/data/orgs.csv';
        
        $i = 0;
        
        $added = array();
        
        if (($handle = fopen($csvLocation, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                
              if($i++ == 0)  
                continue;
                
              $user = new User();
              $user->setFirstname(array_rand($names));
              $user->setLastname(array_rand($lastnames));
              $user->setEmail(strtolower($data[2]));
              $user->setPhone('1111111111');
              $user->setPlainPassword('lukasz.123');        
              $organization = new Organization();
              
              $organization->setName('Some org');
              $organization->setEventLocation('Some location');
              $organization->setEventTime(new \DateTime());
              $organization->setCommunity($data[0]);
              $organization->setState($data[1]);
                
              $user->setOrganization($organization);
                
              $manager->persist($user);
              
              $added[$user->getEmail()] = $user;
              
            }
            fclose($handle);
        }
      
        $manager->flush();
    }
}

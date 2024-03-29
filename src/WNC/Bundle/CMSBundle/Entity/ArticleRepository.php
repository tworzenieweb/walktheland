<?php

namespace WNC\Bundle\CMSBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
  
  public function findAllWithMedias()
  {
    $dql = "SELECT a, p FROM WNCCMSBundle:Article a INNER JOIN a.picture p";
    return $this->getEntityManager()->createQuery($dql)
            ->execute();
    
  }
  
}

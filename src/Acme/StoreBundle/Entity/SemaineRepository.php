<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SemaineRepository extends EntityRepository
{
    public function findJour()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r.jour FROM AcmeStoreBundle:Semaine r '
            )
            ->getResult();
    }
    
}
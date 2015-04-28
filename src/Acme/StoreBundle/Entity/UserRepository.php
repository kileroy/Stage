<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function delete()
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:User'
            )
            ->getResult();
    }
    public function annuler($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:User as r '
              . 'WHERE r.id = :id'
            )
            ->setParameter('id', $id)
            ->getResult();
    }
}
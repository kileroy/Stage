<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LocalRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AcmeStoreBundle:Local p ORDER BY p.num ASC'
            )
            ->getResult();
    }
    public function delete()
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:Local'
            )
            ->getResult();
    }
    public function annuler($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:Local as r '
              . 'WHERE r.id = :id'
            )
            ->setParameter('id', $id)
            ->getResult();
    }
}
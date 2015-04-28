<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MaterielsRepository extends EntityRepository
{
    public function findAllOrderedByName()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AcmeStoreBundle:Materiels p ORDER BY p.nom ASC'
            )
            ->getResult();
    }
    public function delete()
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:Materiels'
            )
            ->getResult();
    }
    public function annuler($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:Materiels as r '
              . 'WHERE r.id = :id'
            )
            ->setParameter('id', $id)
            ->getResult();
        
    }
}
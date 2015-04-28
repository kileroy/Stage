<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ReserveLRepository extends EntityRepository
{
    public function colision($id, $deb, $fin, $date)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r.id FROM AcmeStoreBundle:ReserveL r '
              . 'WHERE r.perioded <= :fin AND r.periodef >= :deb '
              . 'AND r.date = :date AND r.local = :id'
            )
            ->setParameter('id', $id)
            ->setParameter('deb', $deb)
            ->setParameter('fin', $fin)
            ->setParameter('date', $date)
            ->getResult();
    }
    public function reservation($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AcmeStoreBundle:ReserveL r '
              . 'WHERE r.user = :id ORDER BY r.date'
            )
            ->setParameter('id', $id)
            ->getResult();
    }
    public function horraire()//$day
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AcmeStoreBundle:ReserveL r '
              //. 'WHERE DAYOFWEEK(r.date) = ":day" '
              . 'ORDER BY r.local, r.date, r.perioded'
            )
            //->setParameter('day', $day)
            ->getResult();
        
    }
    //Suppresion
    public function delete()
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:ReserveL'
            )
            ->getResult();
    }
    public function annuler($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:ReserveL as r '
              . 'WHERE r.id = :id'
            )
            ->setParameter('id', $id)
            ->getResult();
    }
}
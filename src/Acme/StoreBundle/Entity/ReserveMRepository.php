<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ReserveMRepository extends EntityRepository
{
    public function colision($id, $deb, $fin, $date)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r.id FROM AcmeStoreBundle:ReserveM r '
              . 'WHERE r.perioded <= :fin AND r.periodef >= :deb '
              . 'AND r.date = :date AND r.materiel = :id'
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
                'SELECT r FROM AcmeStoreBundle:ReserveM r '
              . 'WHERE r.user = :id ORDER BY r.date'
            )
            ->setParameter('id', $id)
            ->getResult();
    }
    public function horraire()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r FROM AcmeStoreBundle:ReserveM r '
              . 'ORDER BY r.materiel,r.date ,r.perioded'
            )
            ->getResult();
    }
    //Suppression
    public function delete()
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:ReserveM'
            )
            ->getResult();
    }
    public function annuler($id)
    {
        return $this->getEntityManager()
            ->createQuery(
                'DELETE FROM AcmeStoreBundle:ReserveM as r '
              . 'WHERE r.id = :id'
            )
            ->setParameter('id', $id)
            ->getResult();
    }
}
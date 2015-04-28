<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Acme\StoreBundle\Entity\ReserveMRepository")
 * @ORM\Table(name="reserve_m")
 * @IgnoreAnnotation("fn")
 */
class ReserveM
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    protected $date;

    /**
     * @ORM\Column(type="integer")
     */
    protected $perioded;
    /**
     * @ORM\Column(type="integer")
     */
    protected $periodef;

    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $date
     * @return ReserveM
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param integer $perioded
     * @return ReserveM
     */
    public function setPerioded($perioded)
    {
        $this->perioded = $perioded;
        return $this;
    }

    /**
     * @return integer 
     */
    public function getPerioded()
    {
        return $this->perioded;
    }

    /**
     * @param integer $periodef
     * @return ReserveM
     */
    public function setPeriodef($periodef)
    {
        $this->periodef = $periodef;
        return $this;
    }

    /**
     * @return integer 
     */
    public function getPeriodef()
    {
        return $this->periodef;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Materiels", inversedBy="reserve_m")
     * @ORM\JoinColumn(name="materiel_id", referencedColumnName="id")
     */
    protected $materiel;
    
    /**
     * @param \Acme\StoreBundle\Entity\Materiels $materiel
     * @return ReserveM
     */
    public function setMateriel(\Acme\StoreBundle\Entity\Materiels $materiel = null)
    {
        $this->materiel = $materiel;
        return $this;
    }

    /**
     * @return \Acme\StoreBundle\Entity\Materiels 
     */
    public function getMateriel()
    {
        return $this->materiel;
    }
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="reserve_m")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @param \Acme\StoreBundle\Entity\User $user
     * @return ReserveM
     */
    public function setUser(\Acme\StoreBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \Acme\StoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}

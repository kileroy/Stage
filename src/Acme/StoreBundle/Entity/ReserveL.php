<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Acme\StoreBundle\Entity\ReserveLRepository")
 * @ORM\Table(name="reserve_l")
 * @IgnoreAnnotation("fn")
 */
class ReserveL
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
     * @return ReserveL
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
     * @return ReserveL
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
     * @return ReserveL
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
     * @ORM\ManyToOne(targetEntity="Local", inversedBy="reserve_l")
     * @ORM\JoinColumn(name="local_id", referencedColumnName="id")
     */
    protected $local;

    /**
     * @param \Acme\StoreBundle\Entity\Local $local
     * @return ReserveL
     */
    public function setLocal(\Acme\StoreBundle\Entity\Local $local = null)
    {
        $this->local = $local;
        return $this;
    }

    /**
     * @return \Acme\StoreBundle\Entity\Local 
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="reserve_l")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @param \Acme\StoreBundle\Entity\User $user
     * @return ReserveL
     */
    public function setUser(\Acme\StoreBundle\Entity\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return \Acme\StoreBundle\Entity\user 
     */
    public function getUser()
    {
        return $this->user;
    }
}

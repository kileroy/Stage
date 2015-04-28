<?php

namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="local")
 * @IgnoreAnnotation("fn")
 * @ORM\Entity(repositoryClass="Acme\StoreBundle\Entity\LocalRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Local
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $num;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Local
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * @return string 
     */
    public function getNum()
    {
        return $this->num;
    }
    
    /**
     * @param string $description
     * @return Local
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * @ORM\OneToMany(targetEntity="ReserveL", mappedBy="local")
     */
    protected $reserve;
    //Sais même pas a quoi ça sert, puyisque toute marche sans...
    /*public function __construct()
    {
        $this->reserve = new ArrayCollection();
    }

    /**
     * @param \Acme\StoreBundle\Entity\ReserveL $reserve
     * @return Local
     */
    public function addReserve(\Acme\StoreBundle\Entity\ReserveL $reserve)
    {
        $this->reserve[] = $reserve;

        return $this;
    }

    /**
     * @param \Acme\StoreBundle\Entity\ReserveL $reserve
     */
    public function removeReserve(\Acme\StoreBundle\Entity\ReserveL $reserve)
    {
        $this->reserve->removeElement($reserve);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReserve()
    {
        return $this->reserve;
    }
    
    public function __toString(){
        return $num;
    }
}

<?php
namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="materiels")
 * @ORM\Entity(repositoryClass="Acme\StoreBundle\Entity\MaterielsRepository")
 * @ORM\HasLifecycleCallbacks()
 * @IgnoreAnnotation("fn")
 */
class Materiels
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $nom;

    /**
     * @ORM\Column(type="text")
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
     * @return Materiels
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $description
     * @return Materiels
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
     * @ORM\OneToMany(targetEntity="ReserveM", mappedBy="materiels")
     */
    protected $reserve;
    //Sais même pas a quoi ça sert, puyisque toute marche sans...
    /*public function __construct()
    {
        $this->reserve = new ArrayCollection();
    }

    /**
     * @param \Acme\StoreBundle\Entity\ReserveM $reserve
     * @return Materiels
     */
    public function addReserve(\Acme\StoreBundle\Entity\ReserveM $reserve)
    {
        $this->reserve[] = $reserve;

        return $this;
    }

    /**
     * @param \Acme\StoreBundle\Entity\ReserveM $reserve
     */
    public function removeReserve(\Acme\StoreBundle\Entity\ReserveM $reserve)
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
}

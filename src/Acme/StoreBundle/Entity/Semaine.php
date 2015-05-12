<?php

namespace Acme\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="semaine")
 * @IgnoreAnnotation("fn")
 * @ORM\Entity(repositoryClass="Acme\StoreBundle\Entity\SemaineRepository")
 * @ORM\HasLifecycleCallbacks()
 */
//
class Semaine
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $jour;
    
    /**
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $jour
     * @return Local
     */
    public function setJour($jour)
    {
        $this->jour = $jour;
        return $this;
    }

    /**
     * @return string 
     */
    public function getJour()
    {
        return $this->jour;
    }
    
    //Sert vraiment a de quoi...?
    public function __toString(){
        return $jour;
    }
    
    //Prend une date en parametre puis lui ajoute une semaine
    public function next($date){
        //$date = $this->getJour();
        $date->modify("+1 week");
        $this->setJour($date);
    }
}

<?php
namespace Acme\StoreBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="Acme\StoreBundle\Entity\UserRepository")
 */
class User implements UserInterface
{
     /**
     * @ORM\Id
     * @ORM\Column(type="integer") 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $username;
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $password;
    /**
     * @ORM\Column(type="array")
     */
    protected $role;
    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $salt;
    #The fuck are you
    
    public function getId()
    {
        return $this->id;
    }
    
    public function eraseCredentials() {
        
    }

    public function getPassword() {
        return $this->password;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @param array $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return array 
     */
    public function getRole()
    {
        return $this->role;
        //Si marche pas, juste a metre ce qui est retourner entre []
    }
    public function getRoles() {
        return [$this->role] ;
    }
    
    /**
     * @ORM\OneToMany(targetEntity="ReserveL", mappedBy="user")
     */
    protected $reservel;
    
    /**
     * @ORM\OneToMany(targetEntity="ReserveM", mappedBy="user")
     */
    protected $reservem;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reservel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->reservem = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param \Acme\StoreBundle\Entity\ReserveL $reservel
     * @return User
     */
    public function addReservel(\Acme\StoreBundle\Entity\ReserveL $reservel)
    {
        $this->reservel[] = $reservel;
        return $this;
    }

    /**
     * @param \Acme\StoreBundle\Entity\ReserveL $reservel
     */
    public function removeReservel(\Acme\StoreBundle\Entity\ReserveL $reservel)
    {
        $this->reservel->removeElement($reservel);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReservel()
    {
        return $this->reservel;
    }

    /**
     * @param \Acme\StoreBundle\Entity\ReserveM $reservem
     * @return User
     */
    public function addReservem(\Acme\StoreBundle\Entity\ReserveM $reservem)
    {
        $this->reservem[] = $reservem;
        return $this;
    }

    /**
     * @param \Acme\StoreBundle\Entity\ReserveM $reservem
     */
    public function removeReservem(\Acme\StoreBundle\Entity\ReserveM $reservem)
    {
        $this->reservem->removeElement($reservem);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReservem()
    {
        return $this->reservem;
    }

}

<?php

namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $user = $this->getUser();
        return $this->render('AcmeStoreBundle:Default:index.html.twig', array('name' => $name) );
    }
    
    public function passAction($name)
    {
        $factory = $this->get('security.encoder_factory');
        $user = new Acme\UserBundle\Entity\User();

        $encoder = $factory->getEncoder($user);
        $password = $encoder->encodePassword('ryanpass', $user->getSalt());
        $user->setPassword($password);
        return $this->render('AcmeStoreBundle:Default:index.html.twig', array('name' => $name));
    }
}

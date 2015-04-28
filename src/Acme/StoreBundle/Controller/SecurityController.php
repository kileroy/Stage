<?php
namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Acme\StoreBundle\Entity\User;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('AcmeStoreBundle:Security:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    
    public function registerAction(Request $request)
    {
        $user = new User();
        //$factory = $this->get('security.encoder_factory');
        
        $form = $this->createFormBuilder($user)
            ->add('username', 'text')
            ->add('password', 'password')
            ->add('S\'inscrire', 'submit')
            ->getForm();
        
        $user->setRole('ROLE_NEW');
        $form->handleRequest($request);

        if ($form->isValid()) {            
            if ($user->getUsername() ){
                
            }
            
            $user->setPassword(hash('sha1', $user->getPassword()));
            
            $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
            //loginAfterAction($user->getUsername(),$user->getPassword());
            return $this->redirect($this->generateUrl('hello'));
        }
        return $this->render('AcmeStoreBundle:Default:new.html.twig', array(
            'form' => $form->createView(), 'err' => "" ));
    }
    //Tentative de faire une connection tout de suite après l'inscription... échec
    public function loginAfterAction(String $name, $pass)
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('AcmeStoreBundle:Security:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
}
<?php

namespace Acme\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Acme\StoreBundle\Entity\Materiels;
use Acme\StoreBundle\Entity\Local;
use Acme\StoreBundle\Entity\ReserveL;
use Acme\StoreBundle\Entity\ReserveM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    /*Affiche le forumulaire pour ajouté un Local
     * Parametre : $request est simplement la requête SQL envoyé
     * Retourne : Une redirection, soit vers la list des Locaux, soit un retour
     * à la page d'ajout. */
    public function newLAction(Request $request){
        $local = new Local();
        $form = $this->createFormBuilder($local)
            ->add('num', 'text')
            ->add('description', 'text', array('required' => false))
            ->add('Ajouter', 'submit')
            ->add('ajouterUnNouveau', 'submit', array('label'  => 'Ajouter à nouveau'))
            ->getForm();
        if($local->getDescription()==null){
            //Pour évité un ajout "null" dans la BD
           $local->setDescription("") ;
        }
        $form->handleRequest($request);
        //Si le formulaire n'as pas d'erreur
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
                    $em->persist($local);
                    $em->flush();
            //Si l'user a appuyer sur le bouton "Ajouter un nouveau" on change la prochaine action
            $nextAction = $form->get('ajouterUnNouveau')->isClicked()
                ? 'ajoutl'
                : 'listL';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return $this->render('AcmeStoreBundle:Default:new.html.twig', array(
            'form' => $form->createView(), 'err' =>"" ));
    }
    /*Affiche le forumulaire pour ajouté un Matériel
     * Parametre : $request est simplement la requête SQL envoyé
     * Retourne : Une redirection, soit vers la list des Matériaux, soit un retour
     * à la page d'ajout. */
    public function newMAction(Request $request){
        $mat = new Materiels();
        $form = $this->createFormBuilder($mat)
            ->add('nom', 'text')
            ->add('description', 'text', array('required' => false))
            ->add('Ajouter', 'submit')
            ->add('ajouterUnNouveau', 'submit', array('label'  => 'Ajouter à nouveau'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
                    $em->persist($mat);
                    $em->flush();
            $nextAction = $form->get('ajouterUnNouveau')->isClicked()
                ? 'ajoutm'
                : 'listM';
            return $this->redirect($this->generateUrl($nextAction));
        }
        return $this->render('AcmeStoreBundle:Default:new.html.twig', array(
            'form' => $form->createView(), 'err' =>"" ));
    }
    /*Affiche le forumulaire pour reserver un Local
     * Parametre : $request est simplement la requête SQL envoyé
     * Retourne : Une redirection de lien vers le profil de l'user. */
    public function reservLAction(Request $request){
        $reserve = new ReserveL();
        $user = $this->getUser();
        $err = ""; //Message d'erreur Vide
        
        $form = $this->createFormBuilder($reserve)
            ->add('date', 'date', array('widget' => 'single_text', 'input' => 'datetime',
            'attr' => array('class' => 'date'), 'data' => new \DateTime(),))
            ->add('perioded', 'choice', array(
            'choices' => array('1' => '8:00h à 8:50h', '2' => '8:55h à 9:45h', '3' => '9:50h à 10:40h', '4' => '10:45h à 11:35h', '5' => '11:40h à 12:30h', '6' => '12:35h à 13:25h', '7' => '13:30h à 14:20h', '8' => '14:25h à 15:15h', '9' => '15:20h à 16:10h', '10' => '16:15h à 17:05h', ),
            'empty_value' => 'Choisissez une Période de Début', 'empty_data'  => null, 'label'  => 'Période de début '))
            ->add('periodef', 'choice', array(
            'choices' => array('2' => '8:55h à 9:45h', '3' => '9:50h à 10:40h', '4' => '10:45h à 11:35h', '5' => '11:40h à 12:30h', '6' => '12:35h à 13:25h', '7' => '13:30h à 14:20h', '8' => '14:25h à 15:15h', '9' => '15:20h à 16:10h', '10' => '16:15h à 17:05h', '11' => '17:10h à 18:00h', ),
            'empty_value' => 'Choisissez une Période de Fin', 'empty_data'  => null, 'label'  => 'Période de fin '))
            ->add('local', 'entity', array(
            'class' => 'AcmeStoreBundle:Local',
            'property' => 'num', 'label'  => 'Local '))
            ->add('Reserver', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $reserve->setUser($user);
            $date = $reserve->getDate()->format('Y-m-d');
            $loc = $reserve->getLocal();
            //Regarde combien il y a de réservation, avec se local, dans cette tranche d'heure.
            $colision = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:ReserveL')
            ->colision($loc->getId(), $reserve->getPerioded(), $reserve->getPeriodef(), $date);
            $nbColision = count($colision);
            //Si la péridoe de fin est plus tôt que la période de début
            if($reserve->getPerioded() > $reserve->getPeriodef() ){
                $err = 'Vous ne pouvez pas débuté plus tard que vous finisez...';
            //S'il y a colision    
            }else if ($nbColision > 0 ){
                $err = 'Cette plage d\'horaire est en conflit avec une réservation.';
            }else{
                $em = $this->getDoctrine()->getManager();
                        $em->persist($reserve);
                        $em->flush();
                return $this->redirect($this->generateUrl('profil', array('id'=>$user->getId(),) ));
            }
        }
        
        return $this->render('AcmeStoreBundle:Default:new.html.twig', array(
            'form' => $form->createView(), 'err' => $err ));
    }
    
    /*Affiche le forumulaire pour reserver un Matériel
     * Parametre : $request est simplement la requête SQL envoyé
     * Retourne : Une redirection de lien vers le profil de l'user. */
    public function reservMAction(Request $request){
        $reserve = new ReserveM();
        $user = $this->getUser();
        $err = ""; $date = "";
        
        $form = $this->createFormBuilder($reserve)
            ->add('date', 'date', array('widget' => 'single_text', 'input' => 'datetime',
            'attr' => array('class' => 'date'), 'data' => new \DateTime(),))
            ->add('perioded', 'choice', array(
            'choices' => array('1' => '8:00h à 8:50h', '2' => '8:55h à 9:45h', '3' => '9:50h à 10:40h', '4' => '10:45h à 11:35h', '5' => '11:40h à 12:30h', '6' => '12:35h à 13:25h', '7' => '13:30h à 14:20h', '8' => '14:25h à 15:15h', '9' => '15:20h à 16:10h', '10' => '16:15h à 17:05h', ),
            'empty_value' => 'Choisissez une Période de Début', 'empty_data'  => null, 'label'  => 'Période de début '))
            ->add('periodef', 'choice', array(
            'choices' => array('2' => '8:55h à 9:45h', '3' => '9:50h à 10:40h', '4' => '10:45h à 11:35h', '5' => '11:40h à 12:30h', '6' => '12:35h à 13:25h', '7' => '13:30h à 14:20h', '8' => '14:25h à 15:15h', '9' => '15:20h à 16:10h', '10' => '16:15h à 17:05h', '11' => '17:10h à 18:00h', ),
            'empty_value' => 'Choisissez une Période de Fin', 'empty_data'  => null, 'label'  => 'Période de fin '))
            ->add('materiel', 'entity', array(
            'class' => 'AcmeStoreBundle:Materiels',
            'property' => 'nom',))
            ->add('Reserver', 'submit')
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $reserve->setUser($user);
            $date = $reserve->getDate()->format('Y-m-d');
            $mat = $reserve->getMateriel();
            $colision = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:ReserveM')
            ->colision($mat->getId(), $reserve->getPerioded(), $reserve->getPeriodef(), $date);
            $nbColision = count($colision);
            
            if($reserve->getPerioded() > $reserve->getPeriodef() ){
                $err = 'Vous ne pouveau pas débuté plus tard que vous finisez...';
            }else if ($nbColision > 0 ){
                $err = 'Cette plage d\'horaire est en conflit avec une réservation.';
            }else{
                $em = $this->getDoctrine()->getManager();
                        $em->persist($reserve);
                        $em->flush();
                return $this->redirect($this->generateUrl('profil', array('id'=>$user->getId(),) ));
            }
        }
        return $this->render('AcmeStoreBundle:Default:new.html.twig', array(
            'form' => $form->createView(), 'err' => $err, ));
    }
    /* Affiche la liste de touts les Locaux
     * Parametre : /
     * Retourne : La Page avec les parametre avec la liste. */
    public function localAction(){
        
        $local = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Local')
            ->findAll();
        return $this->render('AcmeStoreBundle:Default:showl.html.twig', array(
            'show' => $local, ));
        
    }
    /* Affiche la liste de touts les Matériaux
     * Parametre : /
     * Retourne : La Page avec les parametre avec la liste. */
    public function materielAction(){
        $mat = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Materiels')
            ->findAll();
        return $this->render('AcmeStoreBundle:Default:showm.html.twig', array(
            'show' => $mat, 'strict_variables'=> false));
    }
    /* Affiche la liste de touts les Utilisateurs
     * Parametre : /
     * Retourne : La Page avec les parametre avec la liste. */
    public function userAction(){
        
        $user = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:User')
            ->findAll();
        return $this->render('AcmeStoreBundle:Default:user.html.twig', array(
            'show' => $user, ));
    }
    /* Affiche le profil contenant les réservation de l'utilisateur
     * Parametre : $request est la requete SQL, utiliser pour changer le rôle de l'utilisateur 
     *             $id est l'ID de l'utilisateur
     * Retourne : La Page avec les parametre avec la liste. */
    public function profilAction(Request $request, $id){
        //Cherche l'utilisateur concerner
        $user = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:User')
            ->find($id);
        //Et cherche ses réservations
        $reserveL = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:ReserveL')
            ->reservation($id);
        $reserveM = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:ReserveM')
            ->reservation($id);
        //Affiche les heures des périodes à la place des simple int de la BD
        $hDe = [
        1 => "8:00h", 2 => "8:55h", 3 => "9:50h", 4 => "10:45h", 5 => "11:40h",
        6 => "12:35h", 7 => "13:30h", 8 => "14:25h", 9 => "15:20h", 10 => "16:15h"];
        $hA = [
        2 => "9:45h", 3 => "10:40h", 4 => "11:35h", 5 => "12:30h", 6 => "13:25h",
        7 => "14:20h", 8 => "15:15h", 9 => "16:10h", 10 => "17:05h", 11 => "18:00h"];
        //Changement de rôle
        $form = $this->createFormBuilder($user)
            ->add('role', 'choice', array(
            'choices' => array('ROLE_NEW' => 'Nouveau', 'ROLE_USER' => 'Utilisateur', 
                'ROLE_ADMIN' => 'Administrateur'),'empty_value' => false))
            ->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }
        
        return $this->render('AcmeStoreBundle:Default:profil.html.twig', array(
            'show' => $user, 'local' => $reserveL, 'materiel' => $reserveM, 
            'debut' => $hDe, 'fin' => $hA,'form' => $form->createView(),));
    }
    /* Affiche l'Horraire Semaine par Semaine
     * Parametre : $id est le numéro de la semaine
     * Retourne : La page avec l'horraire de la semaine demander dans le URL */
    public function horraireAction($id){
        $local = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Local')
            ->findAll();
        $materiel = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Materiels')
            ->findAll();
        $reserveL = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:ReserveL')
            ->horraire();
        $reserveM = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:ReserveM')
            ->horraire();
        $hDe = [
        1 => "8:00h", 2 => "8:55h", 3 => "9:50h", 4 => "10:45h", 5 => "11:40h",
        6 => "12:35h", 7 => "13:30h", 8 => "14:25h", 9 => "15:20h", 10 => "16:15h"];
        $hA = [
        2 => "9:45h", 3 => "10:40h", 4 => "11:35h", 5 => "12:30h", 6 => "13:25h",
        7 => "14:20h", 8 => "15:15h", 9 => "16:10h", 10 => "17:05h", 11 => "18:00h"];
        
        $semEcol =$this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Semaine')
            ->findJour();
        
        $mois=[
            1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril", 5 => "Mai",
            6 => "Juin", 7 => "Juillet", 8 => "Aout", 9 => "Septembre", 10 => "Octobre",
            11 => "Novembre", 12 => "Décembre"
        ];
        /*On envoie la list des locaux et matériaux pour aficher l'horraire avec leur nom même sans réservation sous eux
          avec les réservation pour les afficher sous*/
        return $this->render('AcmeStoreBundle:Default:horraire.html.twig', array(
           'local' => $reserveL, 'materiel' => $reserveM, 'debut' => $hDe, 'fin' => $hA, 
           'id' => $id, 'semaine' => $semEcol, "mois" => $mois , 'locaux' => $local,
           'materiaux' => $materiel,));
    }
    /* Supprime "x" avec le ID donner dans la table donné, marche avec un Switch addapté avec le URL.
     * Parametre : $supp est l'endroit où on le veux supprimer l'objet, un local, un matériaux, etc.
     *             $id est l'ID de l'objet a supprimer dans l'endroit contenut dans le $supp
     * Retourne : Une redirection vers la page de List ou on l'a supprimer l'objet, 
     * ou sur le profil de l'utilisateur si c'est une Réservation. */
    public function supIdAction($supp, $id){
        //Pour pouvoir permetre au Super-Admin de supprimer sans que ça bug pour "x" raison
        $Sadmin = "non";
        //On met 'oui' si c'est bien l'utilisateur courant
        if ( $this->get('security.context')->isGranted('ROLE_SUPER')){
            $Sadmin = "oui";
        //On empèche d'acceder à $user si le SA est co, puisqu'il n'Est pas dnas la BD
        }else
            $user = $this->getUser();
        $reserveM = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:ReserveM')
            ->find($id);
        //On prend le parametre qui est dans le URL pour savoir où se situt l'obejt qu'on à demander à supprimer dans la BD
        switch ($supp){
            case "reservl" :
                $reserveL = $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveL')
                ->find($id);
                //Si c'est la SA qui est co, ou que c'Est l'utilisateur qui à 
                //faite cette réservation, ou encore que c'Est un admin ou le laisse supprimer
                if($Sadmin == "oui" || $user->getId() == $reserveL->getUser()->getId() || 
                        $this->get('security.context')->isGranted('ROLE_ADMIN')){
                //Sinon, on affiche un petit message d'erreur dans la Page, 
                //mais normalement afficher qu'en jouant avec le URL     
                }else{
                    throw new AccessDeniedException('Qu\'essayez-vous de faire là?');
                }
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveL')
                ->annuler($id);
                return $this->redirect($this->generateUrl('profil', 
                        array('id' => $reserveL->getUser()->getId())));
            case "reservm" :
                $reserveM = $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveM')
                ->find($id);
                if($Sadmin == "oui" || $user->getId() == $reserveM->getUser()->getId() || 
                        $this->get('security.context')->isGranted('ROLE_ADMIN')){
                }else{
                    throw new AccessDeniedException('Qu\'essayez-vous de faire là?');
                }
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveM')
                ->annuler($id);
                return $this->redirect($this->generateUrl('profil', 
                        array('id' => $reserveM->getUser()->getId())));
            case "local" :
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:Local')
                ->annuler($id);
                return $this->redirect($this->generateUrl('listL'));
            case "materiel" :
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:Materiels')
                ->annuler($id);  
                return $this->redirect($this->generateUrl('listM'));
            case "user" :
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:User')
                ->annuler($id);  
                return $this->redirect($this->generateUrl('user'));
        }
    }
    /* Vide une table au complèt en supprimant entièrement les entré, la table est envoyé par le URL
     * Parametre : $supp est l'endroit où on le veux supprimer , local,  matériel, etc.
     * Retourne : La Page avec les parametre avec la liste. */
    public function dropAction($supp){
        switch ($supp){
            case "reservl" :
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveL')
                ->delete();
                break;
            case "reservm" :
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveM')
                ->delete();
                break;
            case "local" :
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:Local')
                ->delete();
                break;
            case "materiel" :
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:Materiels')
                ->delete();  
                break;
            case "user" :
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:User')
                ->delete();  
                break;
            case "all" :
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveL')
                ->delete();
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveM')
                ->delete();
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:Local')
                ->delete();
                $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:Materiels')
                ->delete();  
                break;
        }
        return $this->redirect($this->generateUrl('reset'));
    }
    /* Affiche une page simple pour choisir ce que le SA veux vider
     * Parametre : /
     * Retourne : La page */
    public function resetAAction(){
        return $this->render('AcmeStoreBundle:Default:reset.html.twig');
    }
    /* Affiche l'Horraire complèt dans une page HTML simple pour pouvoir imprimer le tout
     * Parametre : $imp est l'horraire qu'on veux imprimer, entre les Locaux et Matériaux, gérer par un Switch. Reçu par le URL
     * Retourne : La page avec l'horraire entier demander dans le $imp */
    public function impAction($imp){
        
        $materiel = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Materiels')
            ->findAll();
        $hDe = [
        1 => "8:00h", 2 => "8:55h", 3 => "9:50h", 4 => "10:45h", 5 => "11:40h",
        6 => "12:35h", 7 => "13:30h", 8 => "14:25h", 9 => "15:20h", 10 => "16:15h"];
        $hA = [
        2 => "9:45h", 3 => "10:40h", 4 => "11:35h", 5 => "12:30h", 6 => "13:25h",
        7 => "14:20h", 8 => "15:15h", 9 => "16:10h", 10 => "17:05h", 11 => "18:00h"];
        
        $semEcol =$this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Semaine')
            ->findJour();
        
        $mois=[
            1 => "Janvier", 2 => "Février", 3 => "Mars", 4 => "Avril", 5 => "Mai",
            6 => "Juin", 7 => "Juillet", 8 => "Aout", 9 => "Septembre", 10 => "Octobre",
            11 => "Novembre", 12 => "Décembre"];
        
        switch ($imp){
            case "local" :
                $objet = $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:Local')
                ->findAll();
                $reserve = $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveL')
                ->horraire();
                return $this->render('AcmeStoreBundle:Default:impLoc.html.twig', array(
                'local' => $reserve, 'debut' => $hDe, 'fin' => $hA, 
                'semaine' => $semEcol, 'mois' => $mois, 'locaux' => $objet,));
            case "materiel" :
                $objet = $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:Materiels')
                ->findAll();
                $reserve = $this->getDoctrine()
                ->getRepository('AcmeStoreBundle:ReserveM')
                ->horraire();
                return $this->render('AcmeStoreBundle:Default:impMat.html.twig', array(
                'materiel' => $reserve, 'debut' => $hDe, 'fin' => $hA, 
                'semaine' => $semEcol, 'mois' => $mois, 'materiaux' => $objet,));
        }
    }
    /* Affiche 18 Lundi qui eux sont utiliser pour faire les semaines d'école
     * Parametre : /
     * Retourne : La page avec la liste des 18 [Pour être sur, avec les congés et tout] 
     * lundi qui débute les semaines */
    public function semaineAction(){
        $semaine = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Semaine')
            ->findAll();
        
        return $this->render('AcmeStoreBundle:Default:semaine.html.twig', array(
            'sem' => $semaine,));
    }
    /* Affiche un mini "formulaire" avec seulement un 'date' pour pouvoir changer le lundi demander
     * Parametre : $request est la requete SQL
     *             $id est le numéro de la Semaine selectionner à changer
     * Retourne : La page avec un 'date' pour changer la date du Lundi*/
    public function changerAction(Request $request, $id){
        $semaine = $this->getDoctrine()
            ->getRepository('AcmeStoreBundle:Semaine')
            ->find($id);
        $err = "";
        
        if ($semaine->getId() >= 18){
            $form = $this->createFormBuilder($semaine)
            ->add('jour', 'date', array('widget' => 'single_text', 'input' => 'datetime',
            'attr' => array('class' => 'lundi'),))
            ->add('valider', 'submit')
            ->getForm();
        }else{
            $form = $this->createFormBuilder($semaine)
            ->add('jour', 'date', array('widget' => 'single_text', 'input' => 'datetime',
            'attr' => array('class' => 'lundi'),))
            ->add('valider', 'submit')
            ->add('validerEtProchain', 'submit')
            ->getForm();
        }
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            //Devrais être inutile, mais juste au cas ou la date ne serrait pas un lundi
            if (date_format($semaine->getJour(),'N') !=1 )
                $err = "Cette date n'est pas un Lundi.";
            else{
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $nextAction = $form->get('validerEtProchain')->isClicked()
                    ? 1
                    : 0;
            //Si c'est 1, ça veux dire qu'on a cliquer sur "Valider et Prochain" et on envois le prochain lundi a changer.
            if ($nextAction == 1)
            return $this->redirect($this->generateUrl('changer', array ('id' => $id+1)));
            else
            return $this->redirect($this->generateUrl('semaine'));
            }
        }    
        return $this->render('AcmeStoreBundle:Default:new.html.twig', array(
            'sem' => $semaine, 'form' => $form->createView(), 'err' => $err,));
    }
}
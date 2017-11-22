<?php 

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

use AppBundle\Form\TicketType;
use AppBundle\Entity\Ticket;     

use Doctrine\ORM\EntityManagerInterface;

use \AppBundle\Entity\Repreneurs;
use AppBundle\Entity\Tournee;
use AppBundle\Entity\Chauffeurs;
use \AppBundle\Entity\Vehicules;
use \AppBundle\Entity\Dechets;
use \AppBundle\Entity\Stats;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use EasyCorp\Bundle\EasyAdminBundle\Exception\EntityRemoveException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\ForbiddenActionException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\NoEntitiesConfiguredException;
use EasyCorp\Bundle\EasyAdminBundle\Exception\UndefinedEntityException;
use EasyCorp\Bundle\EasyAdminBundle\Form\Util\LegacyFormHelper;
use Pagerfanta\Pagerfanta;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TicketController extends BaseAdminController {    

    
     protected function newAction() 
    { 

        $this->dispatch(EasyAdminEvents::PRE_NEW);

        $entity = $this->executeDynamicMethod('createNew<EntityName>Entity');

        $easyadmin = $this->request->attributes->get('easyadmin');
        $easyadmin['item'] = $entity;
        $this->request->attributes->set('easyadmin', $easyadmin);

        $fields = $this->entity['new']['fields'];

        $newForm = $this->executeDynamicMethod('create<EntityName>NewForm', array($entity, $fields));
        
        $newForm->handleRequest($this->request);
        if ($newForm->isSubmitted() && $newForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_PERSIST, array('entity' => $entity));

            $this->executeDynamicMethod('prePersist<EntityName>Entity', array($entity));
            
            $ticket = $newForm->getData();
            $em = $this->getDoctrine()->getManager();
            $query=$em->createQuery('SELECT t FROM AppBundle:Ticket t WHERE t.Nticket = :Nticket')->setParameter('Nticket',$ticket->getNticket())->getResult();
            
            if (count($query)== 0){
                
                $rep=$em->createQuery('SELECT r FROM AppBundle:Repreneurs r WHERE r.id = :id')->setParameter('id',$ticket->getHiddenRepreneurs())
                    ->getResult();

                $date=$ticket->getDatePesage();
                $annee=substr($date, -4, 4);
                $mois=substr($date, 3, 2);
                $stats=$em->createQuery('SELECT s FROM AppBundle:Stats s WHERE s.repreneurs = :repreneurs AND s.Annee = :annee')->setParameter('repreneurs',$rep[0])
                        ->setParameter('annee',$annee)
                        ->getResult();
                
                if(count($stats)== 1){      
                    $this->ajoutStats($mois,$stats,$ticket);
                }
                else{    
                    $this->creerStats($mois,$rep,$ticket,$annee);
                }   
                              
                $idVehicules=$entity->getHiddenVehicules();
                $vehicules=$this->getVehiculesbyID($idVehicules);
                $idTournee=$entity->getHiddenTournee();
                $tournee=$this->getTourneebyID($idTournee);
                $idChauffeurs=$entity->getHiddenChauffeurs();
                $chauffeurs=$this->getChauffeursbyID($idChauffeurs);
                $idRepreneurs=$entity->getHiddenRepreneurs();
                $repreneurs=$this->getRepreneursbyID($idRepreneurs);
                $idDechets=$entity->getHiddenDechets();
                $dechets=$this->getDechetsbyID($idDechets);

                $catDechets=$this->getDechetsbyID($entity->getCategorieDechets())->getCategorie(); 
                
                $entity->setVehicules($vehicules); 
                $entity->setTournee($tournee);
                $entity->setChauffeurs($chauffeurs);
                $entity->setRepreneurs($repreneurs);
                $entity->setDechets($dechets);
             
                $this->em->persist($entity);
                $this->em->flush();

                $this->dispatch(EasyAdminEvents::POST_PERSIST, array('entity' => $entity));

                return $this->redirectToReferrer();
            }

            $this->dispatch(EasyAdminEvents::POST_NEW, array(
                'entity_fields' => $fields,
                'form' => $newForm,
                'entity' => $entity,
            ));
        }
        
        return $this->render($this->entity['templates']['new'], array(
            'form' => $newForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
        ));
    }

    protected function editAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_EDIT);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];
        
        //$entity->setHiddenTournee($entity->getTournee()->getNom());
        
        //$entity->setRemarque($entity->getTournee()->getNom());
        
        if ($this->request->isXmlHttpRequest() && $property = $this->request->query->get('property')) {
            $newValue = 'true' === mb_strtolower($this->request->query->get('newValue'));
            $fieldsMetadata = $this->entity['list']['fields'];

            if (!isset($fieldsMetadata[$property]) || 'toggle' !== $fieldsMetadata[$property]['dataType']) {
                throw new \RuntimeException(sprintf('The type of the "%s" property is not "toggle".', $property));
            }

            $this->updateEntityProperty($entity, $property, $newValue);

            return new Response((string) $newValue);
        }

        $fields = $this->entity['edit']['fields'];

        
        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', array($entity, $fields));
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $editForm->handleRequest($this->request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_UPDATE, array('entity' => $entity));

            $this->executeDynamicMethod('preUpdate<EntityName>Entity', array($entity));
            
            $idVehicules=$entity->getHiddenVehicules();
            $vehicules=$this->getVehiculesbyID($idVehicules);
            $idTournee=$entity->getHiddenTournee();
            $tournee=$this->getTourneebyID($idTournee);
            $idChauffeurs=$entity->getHiddenChauffeurs();
            $chauffeurs=$this->getChauffeursbyID($idChauffeurs);
            $idRepreneurs=$entity->getHiddenRepreneurs();
            $repreneurs=$this->getRepreneursbyID($idRepreneurs);
            $idDechets=$entity->getHiddenDechets();
            $dechets=$this->getDechetsbyID($idDechets);
            
            $catDechets=$this->getDechetsbyID($entity->getCategorieDechets())->getCategorie(); 
                    
            $entity->setVehicules($vehicules); 
            $entity->setTournee($tournee);
            $entity->setChauffeurs($chauffeurs);
            $entity->setRepreneurs($repreneurs);
            $entity->setDechets($dechets);
            $entity->setCategorieDechets($catDechets);

            $this->em->persist($entity);
            $this->em->flush();

            $this->dispatch(EasyAdminEvents::POST_UPDATE, array('entity' => $entity));

            return $this->redirectToReferrer();
        }

        $this->dispatch(EasyAdminEvents::POST_EDIT);
        
        return $this->render($this->entity['templates']['edit'], array(
            'form' => $editForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    protected function ajoutStats(string $mois, array $stats, Ticket $ticket) 
    {
        $em = $this->getDoctrine()->getManager();
        switch ($mois) {
            case '01':
                $stats[0]->setTotalJan( $stats[0]->getTotalJan()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '02':
                $stats[0]->setTotalFev( $stats[0]->getTotalFev()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '03':
                $stats[0]->setTotalMar( $stats[0]->getTotalMar()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '04':
                $stats[0]->setTotalAvr( $stats[0]->getTotalAvr()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '05':
                $stats[0]->setTotalMai( $stats[0]->getTotalMai()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '06':
                $stats[0]->setTotalJui( $stats[0]->getTotalJui()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
              case '07':
                $stats[0]->setTotalJuil( $stats[0]->getTotalJuil()+$ticket->getPoids());
                  $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '08':
                $stats[0]->setTotalAou( $stats[0]->getTotalAou()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '09':
                $stats[0]->setTotalSep( $stats[0]->getTotalSep()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '10':
                $stats[0]->setTotalOct( $stats[0]->getTotalOct()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '11':
                $stats[0]->setTotalNov( $stats[0]->getTotalNov()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
            case '12':
                $stats[0]->setTotalDec( $stats[0]->getTotalDec()+$ticket->getPoids());
                $stats[0]->setTotalAnnee( $stats[0]->getTotalAnnee()+$ticket->getPoids());
                $em->persist($stats[0]);
                $em->flush();
                break;
        }
    }
    
    protected function creerStats(string $mois, array $rep,  Ticket $ticket, string $annee) 
    {
        $em = $this->getDoctrine()->getManager();
        $stats = new stats();
        switch ($mois) {
            case '01':
                $stats->setTotalJan($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '02':
                $stats->setTotalFev($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '03':
                $stats->setTotalMar($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '04':
                $stats->setTotalAvr($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '05':
                $stats->setTotalMai($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '06':
                $stats->setTotalJui($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '07':
                $stats->setTotalJuil($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '08':
                $stats->setTotalAou($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '09':
                $stats->setTotalSep($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '10':
                $stats->setTotalOct($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '11':
                $stats->setTotalNov($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
            case '12':
                $stats->setTotalDec($ticket->getPoids());
                $stats->setRepreneurs($rep[0]);
                $stats->setAnnee($annee);
                $stats->setTotalAnnee($ticket->getPoids());
                $em->persist($stats);
                $em->flush();
                break;
        }
    }
    protected function getTourneebyID($id) {
        if($id!=null){
            $em = $this->getDoctrine()->getManager();
            $t=$em->createQuery('SELECT t FROM AppBundle:Tournee t WHERE t.id = :id')->setParameter('id',$id)
                   ->getResult();
            return $t[0];
        }
        else
        {
            return null;
        }
    }
    
    protected function getVehiculesbyID($id) {
        if($id!=null){
            $em = $this->getDoctrine()->getManager();
            $v=$em->createQuery('SELECT v FROM AppBundle:Vehicules v WHERE v.id = :id')->setParameter('id',$id)
                   ->getResult();
            return $v[0];
        }
        else
        {
            return null;
        }    
    }
    
    protected function getChauffeursbyID($id) {
        if($id!=null){
            $em = $this->getDoctrine()->getManager();
            $c=$em->createQuery('SELECT c FROM AppBundle:Chauffeurs c WHERE c.id = :id')->setParameter('id',$id)
                   ->getResult();
            return $c[0];
        }
        else
        {
            return null;
        }
    }
    
    protected function getRepreneursbyID($id) {
            if($id!=null){
            $em = $this->getDoctrine()->getManager();
            $r=$em->createQuery('SELECT r FROM AppBundle:Repreneurs r WHERE r.id = :id')->setParameter('id',$id)
                   ->getResult();
            return $r[0];
        }
        else
        {
            return null;
        }
    }
    
    protected function getDechetsbyID($id) {
        if($id!=null){
            $em = $this->getDoctrine()->getManager();
            $d=$em->createQuery('SELECT d FROM AppBundle:Dechets d WHERE d.id = :id')->setParameter('id',$id)
                   ->getResult();
            return $d[0];                                                                                                                                             
        }
        else
        {
            return null;
        }
    }
}    
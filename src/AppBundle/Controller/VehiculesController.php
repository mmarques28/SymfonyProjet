<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\StreamedResponse;;

/**
 * Description of VehiculesController
 *
 * @author mmarques
 */
class VehiculesController extends BaseAdminController{
    /**
     * @Route("/exportVehiculesCSV")
    */
    public function exportAction()
    {

        $em = $this->getDoctrine()->getManager();
        $results=$em->createQuery('SELECT s FROM AppBundle:Vehicules s')
                    ->getResult();
        
        $response = new StreamedResponse();
        $response->setCallback(
            function () use ($results) {
                $handle = fopen('php://output', 'r+');
                fwrite($handle, "sep=\t".PHP_EOL);
                fputcsv($handle, array('Id','Numero interne','Marque','Plaque'), chr(9));
                foreach ($results as $row) {
                    //array list fields you need to export
                    $data = $row->toArray($row);
                    fputcsv($handle, $data, chr(9));
                    //fputcsv($handle, $data, chr(9), '"');
                    //fputcsv($handle, $data, ',', '"'); //same default fputcsv($handle, $data);
                }
                fclose($handle);
            }
        );
        //$filename=$this->entity['name'].'.csv';            
        //$response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename=Vehicules.csv');

        return $response;
    }
}

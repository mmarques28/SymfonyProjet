<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use Yoh\JasperReportBundle\Services\ClientService;
use Symfony\Component\DependencyInjection\Container;

use AppBundle\Entity\Tournee;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Ticket; 
use AppBundle\Entity\Stats; 
use AppBundle\Entity\Repreneurs;
use AppBundle\Entity\Chauffeurs; 
use AppBundle\Entity\Vehicules; 
use AppBundle\Entity\Dechets;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Description of TourneeController
 *
 * @author mmarques
 */
class test extends Controller {
    /**
     * @Route("/test")
     */
    public function indexAction(Request $request)
    {

    }
}
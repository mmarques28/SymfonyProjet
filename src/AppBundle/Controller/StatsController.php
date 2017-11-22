<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

/**
 * Description of StatsController
 *
 * @author mmarques
 */

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;


use \AppBundle\Entity\Repreneurs;
use AppBundle\Entity\Ticket; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;


use Yoh\JasperReportBundle\Services\ClientService;
use Symfony\Component\DependencyInjection\Container;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ColumnChart;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StatsController extends BaseAdminController{
    

    public function indexAction(Request $request)
    {
        $this->initialize($request);

        if (null === $request->query->get('entity')) {
            return $this->redirectToBackendHomepage();
        }     

        $repreneurs = $this->getDoctrine()->getRepository(Repreneurs::class)->findAll();
        
        $choices = array();
        foreach($repreneurs as $r) {

            $choices[$r->getNom()] = $r->getId();

        }
        $formOptions = array('repreneurs' => $choices);
        $form = $this->createFormBuilder($formOptions)   
                ->add('Repreneurs',  ChoiceType::class, array( 'choices' => $formOptions['repreneurs']))
                ->add('Temps', ChoiceType::class, array('choices'  => array(
                                                           'Par Mois' => 1,
                                                           'Par Annee' => 2))) 
                ->add('save', SubmitType::class, array('label' => 'Voir Graphique'))
                ->add('save2', SubmitType::class, array('label' => 'Voir Graphique détaillé'))
                ->getForm();
        
        $form->handleRequest($request);
        
        if ( ($form->isSubmitted()) && ($form->isValid()) ){

            $em = $this->getDoctrine()->getManager();
            $r=$form->getData();

            $rep=$em->createQuery('SELECT r FROM AppBundle:Repreneurs r WHERE r.id = :id')->setParameter('id',$r['Repreneurs'])
                    ->getResult();

            if ($r['Temps']==1){

                $year=date("Y");
                $stats=$em->createQuery('SELECT s FROM AppBundle:Stats s WHERE s.repreneurs = :repreneurs AND s.Annee = :annee')->setParameter('repreneurs',$rep[0])
                          ->setParameter('annee',$year)  
                          ->getResult();

                
                if( ($form->get('save')->isClicked()) ){
            
                    if (count($stats)==1){
 
                        $col = new ColumnChart();
                        $col->getData()->setArrayToDataTable([
                        ['Mois', 'Dechets poids'],
                        ['Janvier',intval($stats[0]->getTotalJan())],
                        ['Fevrier', intval($stats[0]->getTotalFev())],
                        ['Mars', intval($stats[0]->getTotalMar())],
                        ['Avril', intval($stats[0]->getTotalAvr())],
                        ['Mai', intval($stats[0]->getTotalMai())],
                        ['Juin', intval($stats[0]->getTotalJui())],
                        ['Juillet', intval($stats[0]->getTotalJuil())],
                        ['Aout', intval($stats[0]->getTotalAou())],
                        ['Septembre', intval($stats[0]->getTotalSep())],
                        ['Octobre', intval($stats[0]->getTotalOct())],
                        ['Novembre', intval($stats[0]->getTotalNov())],
                        ['Decembre', intval($stats[0]->getTotalDec())] ]);
                    
                    
                        $col->getOptions()->setTitle('Statistique par mois');
                        $col->getOptions()->getAnnotations()->setAlwaysOutside(true);
                        $col->getOptions()->getAnnotations()->getTextStyle()->setFontSize(14);
                        $col->getOptions()->getHAxis()->setTitle('Mois');
                        $col->getOptions()->getVAxis()->setTitle('Total poids');
                        $col->getOptions()->setWidth(800);
                        $col->getOptions()->setHeight(400);

                        return $this->render('easy_admin/Stats/list.html.twig', array('ColumnChart' => $col, 'test' => true, 'stats' =>$stats,
                                                                     'form' => $form->createView()));
                    }
                }
                elseif( ($form->get('save2')->isClicked()) ){
                    
                    $stats=$em->createQuery('SELECT s FROM AppBundle:Ticket s WHERE s.Repreneurs = :repreneurs')->setParameter('repreneurs',$rep[0])
                          ->getResult();
                    
                    for ($i = 0; $i < count($stats); $i++) {
                        
                        $d=$em->createQuery('SELECT r FROM AppBundle:Dechets r WHERE r.Categorie = :id')->setParameter('id',$stats[$i]->getCategorieDechets())
                            ->getResult();
                        $stats[$i]->sethiddenDechets($d[0]->getCode());
                    }
                    
                    
                    if (count($stats)>=1){
                     
                        $col = new ColumnChart();
                        $col->getData()->setArrayToDataTable($this->setdata($stats) );
                    
                       
                        $col->getOptions()->setTitle('Statistique par mois détaillé');
                        $col->getOptions()->getAnnotations()->setAlwaysOutside(true);
                        $col->getOptions()->getAnnotations()->getTextStyle()->setFontSize(14);
                        $col->getOptions()->getHAxis()->setTitle('Mois');
                        $col->getOptions()->getVAxis()->setTitle('Total poids');
                        $col->getOptions()->setWidth(800);
                        $col->getOptions()->setHeight(400);
                        $col->getOptions()->setIsStacked(true);

                        return $this->render('easy_admin/Stats/list.html.twig', array('ColumnChart' => $col, 'test' => true, 'stats' =>$stats,
                                                                     'form' => $form->createView()));
                    }
                }
            }
            else{

                $stats=$em->createQuery('SELECT s FROM AppBundle:Stats s WHERE s.repreneurs = :repreneurs')->setParameter('repreneurs',$rep[0])
                          ->getResult();

                if (count($stats)>=1){

                    $col = new ColumnChart();
                    $col->getData()->setArrayToDataTable($this->getdonnee($stats) );

                    $col->getOptions()->setTitle('Statistique par annee');
                    $col->getOptions()->getAnnotations()->setAlwaysOutside(true);
                    $col->getOptions()->getAnnotations()->getTextStyle()->setFontSize(14);
                    $col->getOptions()->getHAxis()->setTitle('Annee');
                    $col->getOptions()->getVAxis()->setTitle('Total poids');
                    $col->getOptions()->setWidth(800);
                    $col->getOptions()->setHeight(400);

                    return $this->render('easy_admin/Stats/list.html.twig', array('ColumnChart' => $col, 'test' => true,
                                                                    'stats' =>$stats,'form' => $form->createView() ));    
               }
            }
        }
        return $this->render('easy_admin/Stats/list.html.twig', array('test' => false, 'form' => $form->createView()));
    }
    
    public function getdonnee(array $arr){

        $data = array();
        $data[0] = ['Mois', 'Dechets poids'];
        $i = 1;
        foreach($arr as $s) {

           $data[$i]= ( [strval($s->getAnnee()),  intval($s->getTotalAnnee())]); 

            $i = $i + 1;
        }
        return $data;
        
    }
    
    public function setdata(array $arr){

        $data = array();
        $data[0] = ['Mois'];
        $data[1]=['Janvier'];
        $data[2]=['Fevrier'];
        $data[3]=['Mars'];
        $data[4]=['Avril'];
        $data[5]=['Mai'];
        $data[6]=['Juin'];
        $data[7]=['Juillet'];
        $data[8]=['Aout'];
        $data[9]=['Septembre'];
        $data[10]=['Octobre'];
        $data[11]=['Novembre'];
        $data[12]=['Decembre'];
        
        $i = 1;
        foreach($arr as $s) {
            if ($i==1){ 
                $data[0][$i]=$s->gethiddenDechets();  
            }
            else{
                $p=1;
                while( $p < count($data[0]) and $s->gethiddenDechets()!=$data[0][$p] ){
                    $p++;
                }
                if($p==count($data[0])){
                    $data[0][$p]=$s->gethiddenDechets();
                }
            } 
            $i = $i + 1;
        }
        for ($i = 1; $i <= 12; $i++) {
            for ($j = 1; $j < count($data[0]); $j++) {
                $data[$i][$j]=0;
            }
        }
        
        foreach($arr as $s) {
            $p=1;
            while( $p < count($data[0]) and $s->gethiddenDechets()!=$data[0][$p] ){
                $p++;
            }
            // $data[0][$p]=$s->gethiddenDechets();
             $x=$this->check($s);
             $j=1;
             while ($j<=12 and $data[$j][0]!=$x){
                 $j++;
             }
             $data[$j][$p]=$data[$j][$p] + intval($s->getPoids ());

        } 
        return $data;
        
    }    
    
    protected function check(Ticket $stats) 
    {
        $date=$stats->getDatePesage();
        $mois=substr($date, 3, 2);
        switch ($mois) {
            case '01':
                return ('Janvier');
                break;
            case '02':
                return ('Fevrier');
                break;
            case '03':
                return ('Mars');     
                break;
            case '04':
                return ('Avril');
                break;
            case '05':
                return ('Mai');
                break;
            case '06':
                return ('Juin');
                break;
            case '07':
                return ('Juillet');
                break;
            case '08':
                return ('Aout');
                break;
            case '09':
                return ('Septembre');
                break;
            case '10':
                return ('Octobre');
                break;
            case '11':
                return ('Novembre');;
                break;
            case '12':
                return ('Decembre');
                break;
        }
    } 
    
    /**
     * @Route("/report")
    */
     public function reportAction(Request $request)
    {
        
        $container = new Container();
        $client = new ClientService($container); 
        
        $client->setJrsHost('127.0.0.1');
        
        $client->setJrsPort('8080');
        $client->setJrsBase('jasperserver');
        $client->setJrsUsername('jasperadmin');
        $client->setJrsPassword('jasperadmin');
        $client->setJrsOrgId(null);
        $client->connect();
        //$client = $this->get('yoh.jasper.report')->getJasperClient();
       
        $format = "pdf";
        $reportUnit = ('/reports/StatsReports/test');
        $params = array(
            'Custom Label 1' => 'Custom Value 1',
            'Custom Label 2' => 'Custom Value 2'
        );

        return $client->generate($reportUnit, $params, $format);
        
    }
    
     /**
     * @Route("/exportStatsCSV")
    */
    public function exportAction()
    {

        $em = $this->getDoctrine()->getManager();
        $results=$em->createQuery('SELECT s FROM AppBundle:Stats s')
                    ->getResult();
        
        $response = new StreamedResponse();
        $response->setCallback(
            function () use ($results) {
                $handle = fopen('php://output', 'r+');
                fwrite($handle, "sep=\t".PHP_EOL);
                fputcsv($handle, array('Id','Annee','TotalJan','TotalFev','TotalMar','TotalAvr','TotalMai','TotalJui','TotalJuil'
                                    ,'TotalAou','TotalSep','TotalOct','TotalNov','TotalDec','Repreneur'), chr(9));
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
        $response->headers->set('Content-Disposition', 'attachment; filename=Stats.csv');

        return $response;
    }
}    

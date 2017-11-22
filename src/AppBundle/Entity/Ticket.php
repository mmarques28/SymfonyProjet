<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ticket
 *
 * @author mmarques
 */
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Ticket")
 */
class Ticket
{   
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
    */
    private $Nticket;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $DatePesage;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Hentree;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Hsortie;
    /**
     * @ORM\ManyToOne(targetEntity="Vehicules", inversedBy="Vehicules")
     * @ORM\JoinColumn(name="Vehicules_id", referencedColumnName="id")
     */
    private $Vehicules;
    
    public function getVehicules() {
        return $this->Vehicules;
    }

    public function setVehicules($Vehicules) {
        $this->Vehicules = $Vehicules;
    }

    private $hiddenVehicules;
    
    public function getHiddenVehicules() {
        return $this->hiddenVehicules;
    }

    public function setHiddenVehicules($hiddenVehicules) {
        $this->hiddenVehicules = $hiddenVehicules;
    }
    
    /**
     * @ORM\ManyToOne(targetEntity="Repreneurs", inversedBy="Repreneurs")
     * @ORM\JoinColumn(name="Repreneurs_id", referencedColumnName="id")
     */
    private $Repreneurs;
    
    public function getRepreneurs() {
        return $this->Repreneurs;
    }

    public function setRepreneurs($Repreneurs) {
        $this->Repreneurs = $Repreneurs;
    }

        
    private $hiddenRepreneurs;
    
    public function getHiddenRepreneurs() {
        return $this->hiddenRepreneurs;
    }

    public function setHiddenRepreneurs($hiddenRepreneurs) {
        $this->hiddenRepreneurs = $hiddenRepreneurs;
    }
      
    /**
     * @ORM\ManyToOne(targetEntity="Tournee", inversedBy="Tournee")
     * @ORM\JoinColumn(name="Tournee_id", referencedColumnName="id")
     */
    private $Tournee;
    
    
    public function getTournee() {
        return $this->Tournee;
    }

    public function setTournee($Tournee) {
        $this->Tournee = $Tournee;
    }
    
    private $hiddenTournee;
    
    public function getHiddenTournee() {
        return $this->hiddenTournee;
    }

    public function setHiddenTournee($hiddenTournee) {
        $this->hiddenTournee = $hiddenTournee;
    }

      
    /**
     * @ORM\ManyToOne(targetEntity="Chauffeurs", inversedBy="Chauffeurs")
     * @ORM\JoinColumn(name="Chauffeurs_id", referencedColumnName="id")
     */
    private $Chauffeurs;
    
    public function getChauffeurs() {
        return $this->Chauffeurs;
    }

    public function setChauffeurs($Chauffeurs) {
        $this->Chauffeurs = $Chauffeurs;
    }

        
    private $hiddenChauffeurs;
    
    public function getHiddenChauffeurs() {
        return $this->hiddenChauffeurs;
    }

    public function setHiddenChauffeurs($hiddenChauffeurs) {
        $this->hiddenChauffeurs = $hiddenChauffeurs;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Dechets", inversedBy="Dechets")
     * @ORM\JoinColumn(name="Dechets_id", referencedColumnName="id")
     */
    private $Dechets;
    
    public function getDechets() {
        return $this->Dechets;
    }

    public function setDechets($Dechets) {
        $this->Dechets = $Dechets;
    }

    private $hiddenDechets;
    
    public function getHiddenDechets() {
        return $this->hiddenDechets;
    }

    public function setHiddenDechets($hiddenDechets) {
        $this->hiddenDechets = $hiddenDechets;
    }

                 
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CategorieDechets;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $Poids;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $Tarif;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $Facture;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $Bonification;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Remarque;
            
    public function getNticket()
    {
        return $this->Nticket;
    }

    public function setNticket($Nticket)
    {
        $this->Nticket = $Nticket;
    }
    
   public function getDatePesage() {
        return $this->DatePesage;
    }

   public function getHentree() {
        return $this->Hentree;
    }

   public function getHsortie() {
        return $this->Hsortie;
    }



    public   function getCategorieDechets() {
        return $this->CategorieDechets;
    }

   public function getPoids() {
        return $this->Poids;
    }

   public function getTarif() {
        return $this->Tarif;
    }

   public function getFacture() {
        return $this->Facture;
    }

   public function getBonification() {
        return $this->Bonification;
    }

   public function getRemarque() {
        return $this->Remarque;
    }

   public function setDatePesage($DatePesage) {
        $this->DatePesage = $DatePesage;
    }

   public function setHentree($Hentree) {
        $this->Hentree = $Hentree;
    }

   public function setHsortie($Hsortie) {
        $this->Hsortie = $Hsortie;
    }


   public function setCategorieDechets($CategorieDechets) {
        $this->CategorieDechets = $CategorieDechets;
    }

   public function setPoids($Poids) {
        $this->Poids = $Poids;
    }

   public function setTarif($Tarif) {
        $this->Tarif = $Tarif;
    }

   public function setFacture($Facture) {
        $this->Facture = $Facture;
    }

   public function setBonification($Bonification) {
        $this->Bonification = $Bonification;
    }

   public function setRemarque($Remarque) {
        $this->Remarque = $Remarque;
    }
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    

}

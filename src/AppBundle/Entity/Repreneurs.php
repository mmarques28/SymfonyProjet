<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of Repreneurs
 *
 * @author mmarques
 */

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="Repreneurs")
 */

class Repreneurs {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Nom;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Adresse;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Telephone;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Fax;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Email;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $SiteWeb;
        
    
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->Nom;
    }

    public function getAdresse() {
        return $this->Adresse;
    }

    public function getTelephone() {
        return $this->Telephone;
    }

    public function getFax() {
        return $this->Fax;
    }

    public function getEmail() {
        return $this->Email;
    }

    public function getSiteWeb() {
        return $this->SiteWeb;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($Nom) {
        $this->Nom = $Nom;
    }

    public function setAdresse($Adresse) {
        $this->Adresse = $Adresse;
    }

    public function setTelephone($Telephone) {
        $this->Telephone = $Telephone;
    }

    public function setFax($Fax) {
        $this->Fax = $Fax;
    }

    public function setEmail($Email) {
        $this->Email = $Email;
    }

    public function setSiteWeb($SiteWeb) {
        $this->SiteWeb = $SiteWeb;
    }
    public function toArray(Repreneurs $repreneurs) {
        $data = array($repreneurs->getId(),
                    $repreneurs->getNom(),
                    $repreneurs->getAdresse(),
                    $repreneurs->getTelephone(),
                    $repreneurs->getFax(),
                    $repreneurs->getEmail(),
                    $repreneurs->getSiteWeb(),
        );
        return  $data;
    }

}

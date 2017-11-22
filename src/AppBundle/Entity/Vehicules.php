<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of Vehicules
 *
 * @author mmarques
 */
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="Vehicules")
 */
class Vehicules {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Ninterne;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Marque;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Plaque;

    
    public function getId() {
        return $this->id;
    }

    public function getNinterne() {
        return $this->Ninterne;
    }

    public function getMarque() {
        return $this->Marque;
    }

    public function getPlaque() {
        return $this->Plaque;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNinterne($Ninterne) {
        $this->Ninterne = $Ninterne;
    }

    public function setMarque($Marque) {
        $this->Marque = $Marque;
    }

    public function setPlaque($Plaque) {
        $this->Plaque = $Plaque;
    }
    public function toArray(Vehicules $vehicules) {
        $data = array($vehicules->getId(),
                    $vehicules->getNinterne(),
                    $vehicules->getMarque(),
                    $vehicules->getPlaque(),
        );
        return  $data;
    }



}

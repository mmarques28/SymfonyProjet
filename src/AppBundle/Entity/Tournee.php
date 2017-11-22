<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of Tournee
 *
 * @author mmarques
 */

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="Tournee")
 */
class Tournee {
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
    private $Frequence;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Remarques;
    
    
    
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->Nom;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNom($Nom) {
        $this->Nom = $Nom;
    }
    public function getFrequence() {
        return $this->Frequence;
    }

    public function getRemarques() {
        return $this->Remarques;
    }

    public function setFrequence($Frequence) {
        $this->Frequence = $Frequence;
    }

    public function setRemarques($Remarques) {
        $this->Remarques = $Remarques;
    }
    public function toArray(Tournee $tournee) {
        $data = array($tournee->getId(),
                    $tournee->getNom(),
                    $tournee->getFrequence(),
                    $tournee->getRemarques(),
        );
        return  $data;
    }

}

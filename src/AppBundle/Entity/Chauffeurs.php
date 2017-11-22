<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of Chauffeurs
 *
 * @author mmarques
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Chauffeurs")
 */

class Chauffeurs {
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
    private $Prenom;
    
    public function getNom() {
        return $this->Nom;
    }

    public function getPrenom() {
        return $this->Prenom;
    }

    public function setNom($Nom) {
        $this->Nom = $Nom;
    }

    public function setPrenom($Prenom) {
        $this->Prenom = $Prenom;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    public function toArray(Chauffeurs $chauffeurs) {
        $data = array($chauffeurs->getId(),
                    $chauffeurs->getNom(),
                    $chauffeurs->getPrenom(),
        );
        return  $data;
    }

}

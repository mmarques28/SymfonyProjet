<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of Dechets
 *
 * @author mmarques
 */

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="Dechets")
 */

class Dechets {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Code;
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Categorie;
    
    
    
    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->Code;
    }

    public function getCategorie() {
        return $this->Categorie;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setCode($Code) {
        $this->Code = $Code;
    }

    public function setCategorie($Categorie) {
        $this->Categorie = $Categorie;
    }
    public function toArray(Dechets $dechets) {
        $data = array($dechets->getId(),
                    $dechets->getCode(),
                    $dechets->getCategorie(),
        );
        return  $data;
    }

}

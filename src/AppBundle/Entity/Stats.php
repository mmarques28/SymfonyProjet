<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

/**
 * Description of Stats
 *
 * @author mmarques
 */
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="Stats")
 */
class Stats {
    /**
    * @ORM\Column(type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    
    private $id;
    
     /**
     * @ORM\ManyToOne(targetEntity="Repreneurs", inversedBy="stats")
     * @ORM\JoinColumn(name="repreneurs_id", referencedColumnName="id")
     */
    private $repreneurs;
    
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalJan;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalFev;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalMar;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalAvr;
        /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalMai;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalJui;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalJuil;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalAou;
        /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalSep;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalOct;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalNov;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalDec;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Annee;
    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $TotalAnnee;
    
    public function getId() {
        return $this->id;
    }

    public function getRepreneurs() {
        return $this->repreneurs;
    }

    public function getTotalJan() {
        return $this->TotalJan;
    }

    public function getTotalFev() {
        return $this->TotalFev;
    }

    public function getTotalMar() {
        return $this->TotalMar;
    }

    public function getTotalAvr() {
        return $this->TotalAvr;
    }

    public function getTotalMai() {
        return $this->TotalMai;
    }

    public function getTotalJui() {
        return $this->TotalJui;
    }

    public function getTotalJuil() {
        return $this->TotalJuil;
    }

    public function getTotalAou() {
        return $this->TotalAou;
    }

    public function getTotalSep() {
        return $this->TotalSep;
    }

    public function getTotalOct() {
        return $this->TotalOct;
    }

    public function getTotalNov() {
        return $this->TotalNov;
    }

    public function getTotalDec() {
        return $this->TotalDec;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setRepreneurs($repreneurs) {
        $this->repreneurs = $repreneurs;
    }

    public function setTotalJan($TotalJan) {
        $this->TotalJan = $TotalJan;
    }

    public function setTotalFev($TotalFev) {
        $this->TotalFev = $TotalFev;
    }

    public function setTotalMar($TotalMar) {
        $this->TotalMar = $TotalMar;
    }

    public function setTotalAvr($TotalAvr) {
        $this->TotalAvr = $TotalAvr;
    }

    public function setTotalMai($TotalMai) {
        $this->TotalMai = $TotalMai;
    }

    public function setTotalJui($TotalJui) {
        $this->TotalJui = $TotalJui;
    }

    public function setTotalJuil($TotalJuil) {
        $this->TotalJuil = $TotalJuil;
    }

    public function setTotalAou($TotalAou) {
        $this->TotalAou = $TotalAou;
    }

    public function setTotalSep($TotalSep) {
        $this->TotalSep = $TotalSep;
    }

    public function setTotalOct($TotalOct) {
        $this->TotalOct = $TotalOct;
    }

    public function setTotalNov($TotalNov) {
        $this->TotalNov = $TotalNov;
    }

    public function setTotalDec($TotalDec) {
        $this->TotalDec = $TotalDec;
    }

    public function getAnnee() {
        return $this->Annee;
    }

    public function setAnnee($Annee) {
        $this->Annee = $Annee;
    }

    public function getTotalAnnee() {
        return $this->TotalAnnee;
    }

    public function setTotalAnnee($TotalAnnee) {
        $this->TotalAnnee = $TotalAnnee;
    }
    public function toArray(Stats $stats) {
        $data = array($stats->getId(),
                    $stats->getAnnee(),
                    $stats->getTotalJan(),
                    $stats->getTotalFev(),
                    $stats->getTotalMar(),
                    $stats->getTotalAvr(),
                    $stats->getTotalMai(),
                    $stats->getTotalJui(),
                    $stats->getTotalJuil(),
                    $stats->getTotalAou(),
                    $stats->getTotalSep(),
                    $stats->getTotalOct(),
                    $stats->getTotalNov(),
                    $stats->getTotalDec(),
                    $stats->getRepreneurs()->getNom(),
        );
        return  $data;
    }

}

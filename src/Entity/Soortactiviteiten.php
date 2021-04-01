<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ClassMetadata;


/**
 * Soortactiviteiten
 *
 * @ORM\Table(name="Soortactiviteiten")
 * @ORM\Entity(repositoryClass="App\Repository\SoortactiviteitRepository")
 */
class Soortactiviteiten
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="naam", type="string", length=255)
     */
    private $naam;

    /**
     * @var int
     *
     * @ORM\Column(name="min_leeftijd", type="integer")
     */
    private $minLeeftijd;

    /**
     * @var int
     *
     * @ORM\Column(name="tijdsduur", type="integer")
     */
    private $tijdsduur;

    /**
     * @var string
     *
     * @ORM\Column(name="prijs", type="decimal", precision=6, scale=2)
     */
    private $prijs;

    /**
     * @ORM\OneToMany(targetEntity="Activiteiten", mappedBy="Soortactiviteiten")
     */

    private $activiteiten;

    public function __construct()
    {
        $this->activiteiten = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set naam
     *
     * @param string $naam
     *
     * @return Soortactiviteiten
     */
    public function setNaam($naam)
    {
        $this->naam = $naam;

        return $this;
    }

    /**
     * Get naam
     *
     * @return string
     */
    public function getNaam()
    {
        return $this->naam;
    }

    /**
     * Set minLeeftijd
     *
     * @param integer $minLeeftijd
     *
     * @return Soortactiviteiten
     */
    public function setMinLeeftijd($minLeeftijd)
    {
        $this->minLeeftijd = $minLeeftijd;

        return $this;
    }

    /**
     * Get minLeeftijd
     *
     * @return int
     */
    public function getMinLeeftijd()
    {
        return $this->minLeeftijd;
    }

    /**
     * Set tijdsduur
     *
     * @param integer $tijdsduur
     *
     * @return Soortactiviteiten
     */
    public function setTijdsduur($tijdsduur)
    {
        $this->tijdsduur = $tijdsduur;

        return $this;
    }

    /**
     * Get tijdsduur
     *
     * @return int
     */
    public function getTijdsduur()
    {
        return $this->tijdsduur;
    }

    /**
     * Set prijs
     *
     * @param string $prijs
     *
     * @return Soortactiviteiten
     */
    public function setPrijs($prijs)
    {
        $this->prijs = $prijs;

        return $this;
    }

    /**
     * Get prijs
     *
     * @return string
     */
    public function getPrijs()
    {
        return $this->prijs;
    }
}


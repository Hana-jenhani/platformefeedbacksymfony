<?php

namespace App\Entity;

use App\Repository\CollaborateurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CollaborateurRepository::class)
 */
class Collaborateur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tache;

    /**
     * @ORM\Column(type="date")
     */
    private $datedebuttache;

    /**
     * @ORM\Column(type="date")
     */
    private $datefintache;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etatavancement;


  
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTache(): ?string
    {
        return $this->tache;
    }

    public function setTache(string $tache): self
    {
        $this->tache = $tache;

        return $this;
    }

    public function getDatedebuttache(): ?\DateTimeInterface
    {
        return $this->datedebuttache;
    }

    public function setDatedebuttache(\DateTimeInterface $datedebuttache): self
    {
        $this->datedebuttache = $datedebuttache;

        return $this;
    }

    public function getDatefintache(): ?\DateTimeInterface
    {
        return $this->datefintache;
    }

    public function setDatefintache(\DateTimeInterface $datefintache): self
    {
        $this->datefintache = $datefintache;

        return $this;
    }

    public function getEtatavancement(): ?string
    {
        return $this->etatavancement;
    }

    public function setEtatavancement(string $etatavancement): self
    {
        $this->etatavancement = $etatavancement;

        return $this;
    }

    

   


    
}

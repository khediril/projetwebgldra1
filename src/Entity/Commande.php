<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Comprod::class, mappedBy="commande")
     */
    private $comprods;

    public function __construct()
    {
        $this->comprods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Comprod>
     */
    public function getComprods(): Collection
    {
        return $this->comprods;
    }

    public function addComprod(Comprod $comprod): self
    {
        if (!$this->comprods->contains($comprod)) {
            $this->comprods[] = $comprod;
            $comprod->setCommande($this);
        }

        return $this;
    }

    public function removeComprod(Comprod $comprod): self
    {
        if ($this->comprods->removeElement($comprod)) {
            // set the owning side to null (unless already changed)
            if ($comprod->getCommande() === $this) {
                $comprod->setCommande(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Commentaire extends AbstractEntity
{
    #[Assert\NotNull()]
    #[ORM\Column]
    private ?string $contenu = null;

    #[ORM\ManyToOne(targetEntity:"App\Entity\User")]
    #[ORM\JoinColumn(nullable:false)]
    private ?User $auteur;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Figure", inversedBy: "commentaires")]
    #[ORM\JoinColumn(nullable:false, onDelete: "CASCADE")]
    private $figure;

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getFigure(): ?Figure
    {
        return $this->figure;
    }

    public function setFigure(Figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }
}

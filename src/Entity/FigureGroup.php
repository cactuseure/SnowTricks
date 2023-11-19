<?php

namespace App\Entity;

use App\Repository\FigureGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FigureGroupRepository::class)]
#[ORM\HasLifecycleCallbacks]
class FigureGroup extends AbstractEntity
{
    #[ORM\OneToMany(mappedBy: 'figureGroup', targetEntity: Figure::class)]
    private Collection $figures;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->figures = new ArrayCollection();
    }

    /**
     * @return Collection<int, Figure>
     */
    public function getFigures(): Collection
    {
        return $this->figures;
    }

    public function addFigure(Figure $figure): static
    {
        if (!$this->figures->contains($figure)) {
            $this->figures->add($figure);
            $figure->setFigureGroup($this);
        }

        return $this;
    }

    public function removeFigure(Figure $figure): static
    {
        if ($this->figures->removeElement($figure)) {
            // set the owning side to null (unless already changed)
            if ($figure->getFigureGroup() === $this) {
                $figure->setFigureGroup(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}

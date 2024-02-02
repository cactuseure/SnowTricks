<?php

namespace App\Entity;

use App\Repository\FigureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: FigureRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Figure extends AbstractEntity
{
    #[Assert\NotNull(message: 'Le champ ne doit pas être vide')]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[Assert\NotNull()]
    #[ORM\Column]
    private ?string $description = null;


    #[Assert\Valid]
    #[ORM\ManyToOne(inversedBy: 'figures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[Assert\NotNull()]
    #[ORM\ManyToOne(inversedBy: 'figures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FigureGroup $figureGroup = null;

    #[ORM\OneToMany(mappedBy: "figure", targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\ManyToMany(targetEntity: MediaObject::class, cascade: ['persist', 'remove'])]
    private Collection $pictures;

    #[ORM\Column]
    private array $youtubeVideos = [];

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?MediaObject $cover = null;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->pictures = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getFigureGroup(): ?FigureGroup
    {
        return $this->figureGroup;
    }

    public function setFigureGroup(?FigureGroup $figureGroup): static
    {
        $this->figureGroup = $figureGroup;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFigure($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(MediaObject $picture): static
    {
        dump($picture);
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
        }

        return $this;
    }

    public function removePicture(MediaObject $picture): static
    {
        $this->pictures->removeElement($picture);

        return $this;
    }

    public function getYoutubeVideos(): array
    {
        return array_unique($this->youtubeVideos);
    }

    public function setYoutubeVideos(array $youtubeVideos): static
    {
        $this->youtubeVideos = $youtubeVideos;

        return $this;
    }

    public function getCover(): ?MediaObject
    {
        return $this->cover;
    }

    public function setCover(MediaObject $cover): static
    {
        $this->cover = $cover;

        return $this;
    }
}

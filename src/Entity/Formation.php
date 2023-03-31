<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Soignant::class)]
    private Collection $soignant;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'formation')]
    private ?Parcours $parcours = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = 'default_formation_image.jpg';

    public function __construct()
    {
        $this->soignant = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Soignant>
     */
    public function getSoignant(): Collection
    {
        return $this->soignant;
    }

    public function addSoignant(Soignant $soignant): self
    {
        if (!$this->soignant->contains($soignant)) {
            $this->soignant->add($soignant);
            $soignant->setFormation($this);
        }

        return $this;
    }

    public function removeSoignant(Soignant $soignant): self
    {
        if ($this->soignant->removeElement($soignant)) {
            // set the owning side to null (unless already changed)
            if ($soignant->getFormation() === $this) {
                $soignant->setFormation(null);
            }
        }

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getParcours(): ?Parcours
    {
        return $this->parcours;
    }

    public function setParcours(?Parcours $parcours): self
    {
        $this->parcours = $parcours;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ParcoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcoursRepository::class)]
class Parcours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'parcours')]
    private ?User $user_id = null;

    #[ORM\OneToMany(mappedBy: 'parcours', targetEntity: Formation::class)]
    private Collection $formation;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private array $chronologie = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tags = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title_quizz1 = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bool_quizz1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title_quizz2 = null;

    #[ORM\Column(nullable: true)]
    private ?bool $bool_quizz2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video_url = null;

    public function __construct()
    {
        $this->formation = new ArrayCollection();
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormation(): Collection
    {
        return $this->formation;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formation->contains($formation)) {
            $this->formation->add($formation);
            $formation->setParcours($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formation->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getParcours() === $this) {
                $formation->setParcours(null);
            }
        }

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

    public function getChronologie(): array
    {
        return $this->chronologie;
    }

    public function setChronologie(?array $chronologie): self
    {
        $this->chronologie = $chronologie;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getTitleQuizz1(): ?string
    {
        return $this->title_quizz1;
    }

    public function setTitleQuizz1(?string $title_quizz1): self
    {
        $this->title_quizz1 = $title_quizz1;

        return $this;
    }

    public function isBoolQuizz1(): ?bool
    {
        return $this->bool_quizz1;
    }

    public function setBoolQuizz1(?bool $bool_quizz1): self
    {
        $this->bool_quizz1 = $bool_quizz1;

        return $this;
    }

    public function getTitleQuizz2(): ?string
    {
        return $this->title_quizz2;
    }

    public function setTitleQuizz2(?string $title_quizz2): self
    {
        $this->title_quizz2 = $title_quizz2;

        return $this;
    }

    public function isBoolQuizz2(): ?bool
    {
        return $this->bool_quizz2;
    }

    public function setBoolQuizz2(?bool $bool_quizz2): self
    {
        $this->bool_quizz2 = $bool_quizz2;

        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->video_url;
    }

    public function setVideoUrl(?string $video_url): self
    {
        $this->video_url = $video_url;

        return $this;
    }
}

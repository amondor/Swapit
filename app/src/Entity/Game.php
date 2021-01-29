<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    private $first_release_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $storyline;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $version_title;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="children")
     */
    private $parent_game;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="parent_game")
     */
    private $children;

    /**
     * @ORM\Column(type="float")
     */
    private $aggregated_rating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $aggregated_rating_count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $follows;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstReleaseDate(): ?\DateTimeInterface
    {
        return $this->first_release_date;
    }

    public function setFirstReleaseDate(\DateTimeInterface $first_release_date): self
    {
        $this->first_release_date = $first_release_date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStoryline(): ?string
    {
        return $this->storyline;
    }

    public function setStoryline(?string $storyline): self
    {
        $this->storyline = $storyline;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getVersionTitle(): ?string
    {
        return $this->version_title;
    }

    public function setVersionTitle(?string $version_title): self
    {
        $this->version_title = $version_title;

        return $this;
    }

    public function getParentGame(): ?self
    {
        return $this->parent_game;
    }

    public function setParentGame(?self $parent_game): self
    {
        $this->parent_game = $parent_game;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParentGame($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParentGame() === $this) {
                $child->setParentGame(null);
            }
        }

        return $this;
    }

    public function getAggregatedRating(): ?float
    {
        return $this->aggregated_rating;
    }

    public function setAggregatedRating(float $aggregated_rating): self
    {
        $this->aggregated_rating = $aggregated_rating;

        return $this;
    }

    public function getAggregatedRatingCount(): ?int
    {
        return $this->aggregated_rating_count;
    }

    public function setAggregatedRatingCount(?int $aggregated_rating_count): self
    {
        $this->aggregated_rating_count = $aggregated_rating_count;

        return $this;
    }

    public function getFollows(): ?int
    {
        return $this->follows;
    }

    public function setFollows(?int $follows): self
    {
        $this->follows = $follows;

        return $this;
    }
}

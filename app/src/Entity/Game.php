<?php

namespace App\Entity;

use App\Services\Igdb;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\GameRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
     * @Groups({"cron"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cron"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"cron"})
     */
    private $first_release_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"cron"})
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"cron"})
     */
    private $storyline;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"cron"})
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"cron"})
     */
    private $version_title;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="children")
     * @Groups({"cron"})
     */
    private $parent_game;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="parent_game")
     * @Groups({"cron"})
     */
    private $children;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"cron"})
     */
    private $aggregated_rating;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"cron"})
     */
    private $aggregated_rating_count;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"cron"})
     */
    private $follows;

    /**
     * @ORM\ManyToMany(targetEntity=Company::class, mappedBy="developed")
     */
    private $involved_companies;

    /**
     * @ORM\ManyToMany(targetEntity=Genre::class, inversedBy="games")
     */
    private $genres;

    /**
     * @ORM\ManyToMany(targetEntity=GameMode::class, inversedBy="games")
     */
    private $modes;

    /**
     * @ORM\ManyToMany(targetEntity=Platform::class, inversedBy="games")
     */
    private $platforms;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->involved_companies = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->modes = new ArrayCollection();
        $this->platforms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): ?self
    {
        $this->id = $id;

        return $this;
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

    public function getFirstReleaseDate(): ?int
    {
        return $this->first_release_date;
    }

    public function setFirstReleaseDate(int $first_release_date): self
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

    /**
     * @return Collection|Company[]
     */
    public function getInvolvedCompanies(): Collection
    {
        return $this->involved_companies;
    }

    public function addInvolvedCompany(Company $involvedCompany): self
    {
        if (!$this->involved_companies->contains($involvedCompany)) {
            $this->involved_companies[] = $involvedCompany;
            $involvedCompany->addDeveloped($this);
        }

        return $this;
    }

    public function removeInvolvedCompany(Company $involvedCompany): self
    {
        if ($this->involved_companies->contains($involvedCompany)) {
            $this->involved_companies->removeElement($involvedCompany);
            $involvedCompany->removeDeveloped($this);
        }

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }

        return $this;
    }

    /**
     * @return Collection|GameMode[]
     */
    public function getModes(): Collection
    {
        return $this->modes;
    }

    public function addMode(GameMode $mode): self
    {
        if (!$this->modes->contains($mode)) {
            $this->modes[] = $mode;
        }

        return $this;
    }

    public function removeMode(GameMode $mode): self
    {
        if ($this->modes->contains($mode)) {
            $this->modes->removeElement($mode);
        }

        return $this;
    }

    /**
     * @return Collection|Platform[]
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): self
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms[] = $platform;
        }

        return $this;
    }

    public function removePlatform(Platform $platform): self
    {
        if ($this->platforms->contains($platform)) {
            $this->platforms->removeElement($platform);
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer")
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
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="OwnGames")
     * @ORM\JoinTable(name="User_Own_Games")
     */
    private $Owners;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="WishGames"),
     * @ORM\JoinTable(name="User_Wish_Games")
     */
    private $Wishers;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->involved_companies = new ArrayCollection();
        $this->Owners = new ArrayCollection();
        $this->Wishers = new ArrayCollection();
        $this->toto = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getOwners(): Collection
    {
        return $this->Owners;
    }

    public function addOwner(User $owner): self
    {
        if (!$this->Owners->contains($owner)) {
            $this->Owners[] = $owner;
            $owner->addOwnGame($this);
        }

        return $this;
    }

    public function removeOwner(User $owner): self
    {
        if ($this->Owners->contains($owner)) {
            $this->Owners->removeElement($owner);
            $owner->removeOwnGame($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getWishers(): Collection
    {
        return $this->Wishers;
    }

    public function addWisher(User $wisher): self
    {
        if (!$this->Wishers->contains($wisher)) {
            $this->Wishers[] = $wisher;
            $wisher->addWishGame($this);
        }

        return $this;
    }

    public function removeWisher(User $wisher): self
    {
        if ($this->Wishers->contains($wisher)) {
            $this->Wishers->removeElement($wisher);
            $wisher->removeWishGame($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getToto(): Collection
    {
        return $this->toto;
    }

    public function addToto(User $toto): self
    {
        if (!$this->toto->contains($toto)) {
            $this->toto[] = $toto;
            $toto->addToto($this);
        }

        return $this;
    }

    public function removeToto(User $toto): self
    {
        if ($this->toto->contains($toto)) {
            $this->toto->removeElement($toto);
            $toto->removeToto($this);
        }

        return $this;
    }
}

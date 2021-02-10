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
    
    /*
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

    /**
     * @ORM\OneToMany(targetEntity=Exchange::class, mappedBy="game")
     */
    private $exchanges;

    /**
     * @ORM\OneToMany(targetEntity=Exchange::class, mappedBy="ownerGame")
     */
    private $targetedExchangesAreReferred;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->involved_companies = new ArrayCollection();
        $this->Owners = new ArrayCollection();
        $this->Wishers = new ArrayCollection();
        $this->toto = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->modes = new ArrayCollection();
        $this->platforms = new ArrayCollection();
        $this->exchanges = new ArrayCollection();
        $this->targetedExchangesAreReferred = new ArrayCollection();
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

    public function removeOwner(User $owner): self
    {
        if ($this->Owners->contains($owner)) {
            $this->Owners->removeElement($owner);
            $owner->removeOwnGame($this);
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

    public function removeWisher(User $wisher): self
    {
        if ($this->Wishers->contains($wisher)) {
            $this->Wishers->removeElement($wisher);
            $wisher->removeWishGame($this);
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

    /**
     * @return Collection|Exchange[]
     */
    public function getExchanges(): Collection
    {
        return $this->exchanges;
    }

    public function addExchange(Exchange $exchange): self
    {
        if (!$this->exchanges->contains($exchange)) {
            $this->exchanges[] = $exchange;
            $exchange->setGame($this);
        }

        return $this;
    }

    public function removeExchange(Exchange $exchange): self
    {
        if ($this->exchanges->removeElement($exchange)) {
            // set the owning side to null (unless already changed)
            if ($exchange->getGame() === $this) {
                $exchange->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Exchange[]
     */
    public function getTargetedExchangesAreReferred(): Collection
    {
        return $this->targetedExchangesAreReferred;
    }

    public function addTargetedExchangesAreReferred(Exchange $targetedExchangesAreReferred): self
    {
        if (!$this->targetedExchangesAreReferred->contains($targetedExchangesAreReferred)) {
            $this->targetedExchangesAreReferred[] = $targetedExchangesAreReferred;
            $targetedExchangesAreReferred->setOwnerGame($this);
        }

        return $this;
    }

    public function removeTargetedExchangesAreReferred(Exchange $targetedExchangesAreReferred): self
    {
        if ($this->targetedExchangesAreReferred->removeElement($targetedExchangesAreReferred)) {
            // set the owning side to null (unless already changed)
            if ($targetedExchangesAreReferred->getOwnerGame() === $this) {
                $targetedExchangesAreReferred->setOwnerGame(null);
            }
        }

        return $this;
    }
}

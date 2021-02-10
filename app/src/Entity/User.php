<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;


    /**
     * @ORM\Column(type="json")
     */
    private $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Offer::class, mappedBy="proposer", orphanRemoval=true)
     */
    private $offers;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class, inversedBy="Owners"),
     * @ORM\JoinTable(name="User_Own_Games")
     */
    private $OwnGames;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class, inversedBy="Wishers"),
     * @ORM\JoinTable(name="User_Wish_Games")
     */
    private $WishGames;

    /**
     * @ORM\OneToMany(targetEntity=Exchange::class, mappedBy="UserOwner")
     */
    private $exchanges;

    /**
     * @ORM\OneToMany(targetEntity=Exchange::class, mappedBy="userProposer")
     */
    private $propositions;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->OwnGames = new ArrayCollection();
        $this->WishGames = new ArrayCollection();
        $this->toto = new ArrayCollection();
        $this->exchanges = new ArrayCollection();
        $this->propositions = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getOwnGames(): Collection
    {
        return $this->OwnGames;
    }

    public function addOwnGame(Game $ownGame): self
    {
        if (!$this->OwnGames->contains($ownGame)) {
            $this->OwnGames[] = $ownGame;
        }

        return $this;
    }

    public function removeOwnGame(Game $ownGame): self
    {
        if ($this->OwnGames->contains($ownGame)) {
            $this->OwnGames->removeElement($ownGame);
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getWishGames(): Collection
    {
        return $this->WishGames;
    }

    public function addWishGame(Game $wishGame): self
    {
        if (!$this->WishGames->contains($wishGame)) {
            $this->WishGames[] = $wishGame;
        }

        return $this;
    }

    public function removeWishGame(Game $wishGame): self
    {
        if ($this->WishGames->contains($wishGame)) {
            $this->WishGames->removeElement($wishGame);
        }

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    /**
     * Set the value of offers
     *
     * @return  self
     */ 
    public function setOffers($offers)
    {
        $this->offers = $offers;

        return $this;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setProposer($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getProposer() === $this) {
                $offer->setProposer(null);
            }
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
            $exchange->setUserOwner($this);
        }

        return $this;
    }

    public function removeExchange(Exchange $exchange): self
    {
        if ($this->exchanges->removeElement($exchange)) {
            // set the owning side to null (unless already changed)
            if ($exchange->getUserOwner() === $this) {
                $exchange->setUserOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Exchange[]
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function addProposition(Exchange $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions[] = $proposition;
            $proposition->setUserProposer($this);
        }

        return $this;
    }

    public function removeProposition(Exchange $proposition): self
    {
        if ($this->propositions->removeElement($proposition)) {
            // set the owning side to null (unless already changed)
            if ($proposition->getUserProposer() === $this) {
                $proposition->setUserProposer(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\UserRepository;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(
 *  fields={"email"},
 *  message="l'email est déjà utilisé"
 * )
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
     * @ORM\Column(type="string", length=255)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", orphanRemoval=true)
     */
    private $comments;

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

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->OwnGames = new ArrayCollection();
        $this->WishGames = new ArrayCollection();
        $this->toto = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(){
        return [
            'role_user' => 'ROLE_USER'
        ];
    }

    public function getSalt(){}

    public function eraseCredentials(){}

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
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
     * @return Collection|Game[]
     */
    public function getToto(): Collection
    {
        return $this->toto;
    }

    public function addToto(Game $toto): self
    {
        if (!$this->toto->contains($toto)) {
            $this->toto[] = $toto;
        }

        return $this;
    }

    public function removeToto(Game $toto): self
    {
        if ($this->toto->contains($toto)) {
            $this->toto->removeElement($toto);
        }

        return $this;
    }


}

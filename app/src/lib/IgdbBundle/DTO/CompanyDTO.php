<?php

namespace App\lib\IgdbBundle\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class CompanyDTO
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
    private $country;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"cron"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class, inversedBy="involved_companies")
     */
    private $developed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"cron"})
     */
    private $slug;

    public function __construct()
    {
        $this->developed = new ArrayCollection();
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

    public function getCountry(): ?int
    {
        return $this->country;
    }

    public function setCountry(?int $country): self
    {
        $this->country = $country;

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

    /**
     * @return Collection|Game[]
     */
    public function getDeveloped(): Collection
    {
        return $this->developed;
    }

    public function addDeveloped(Game $developed): self
    {
        if (!$this->developed->contains($developed)) {
            $this->developed[] = $developed;
        }

        return $this;
    }

    public function removeDeveloped(Game $developed): self
    {
        if ($this->developed->contains($developed)) {
            $this->developed->removeElement($developed);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}

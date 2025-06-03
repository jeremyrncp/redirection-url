<?php

namespace App\Entity;

use App\Repository\DomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DomainRepository::class)]
class Domain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Uri>
     */
    #[ORM\OneToMany(mappedBy: 'domain', targetEntity: Uri::class)]
    private Collection $uris;

    public function __construct()
    {
        $this->uris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Uri>
     */
    public function getUris(): Collection
    {
        return $this->uris;
    }

    public function addUri(Uri $uri): static
    {
        if (!$this->uris->contains($uri)) {
            $this->uris->add($uri);
            $uri->setDomain($this);
        }

        return $this;
    }

    public function removeUri(Uri $uri): static
    {
        if ($this->uris->removeElement($uri)) {
            // set the owning side to null (unless already changed)
            if ($uri->getDomain() === $this) {
                $uri->setDomain(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

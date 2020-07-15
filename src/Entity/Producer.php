<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     itemOperations={"get"},
 *     collectionOperations={"get"},
 *     graphql={
 *       "create"={"security"="is_granted('ROLE_ADMIN')"},
 *       "update"={"security"="is_granted('ROLE_ADMIN')"},
 *       "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProducerRepository")
 */
class Producer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $prefix;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Package", mappedBy="producer", orphanRemoval=true)
     */
    private Collection $package;

    public function __construct()
    {
        $this->package = new ArrayCollection();
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

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * @return Collection|Package[]
     */
    public function getPackage(): Collection
    {
        return $this->package;
    }

    public function addPackage(Package $package): self
    {
        if (!$this->package->contains($package)) {
            $this->package[] = $package;
            $package->setProducer($this);
        }

        return $this;
    }

    public function removePackage(Package $package): self
    {
        if ($this->package->contains($package)) {
            $this->package->removeElement($package);
            // set the owning side to null (unless already changed)
            if ($package->getProducer() === $this) {
                $package->setProducer(null);
            }
        }

        return $this;
    }
}

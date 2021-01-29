<?php

namespace App\Entity;

use App\Repository\DependencyPackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DependencyPackageRepository::class)
 */
class DependencyPackage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $version;

    /**
     * @ORM\Column(type="json")
     */
    private array $composerJson = [];

    /**
     * @ORM\ManyToMany(targetEntity=Version::class, inversedBy="dependencyPackages")
     */
    private $packageVersions;

    public function __construct()
    {
        $this->packageVersions = new ArrayCollection();
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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getComposerJson(): ?array
    {
        return $this->composerJson;
    }

    public function setComposerJson(array $composerJson): self
    {
        $this->composerJson = $composerJson;

        return $this;
    }

    public function getPath(): string
    {
        return \sprintf('%s/storage/%s/%s.zip', \dirname(__DIR__, 2), $this->name, $this->version);
    }

    /**
     * @return Collection|Version[]
     */
    public function getPackageVersions(): Collection
    {
        return $this->packageVersions;
    }

    public function addPackageVersion(Version $packageVersion): self
    {
        if (!$this->packageVersions->contains($packageVersion)) {
            $this->packageVersions[] = $packageVersion;
        }

        return $this;
    }

    public function removePackageVersion(Version $packageVersion): self
    {
        $this->packageVersions->removeElement($packageVersion);

        return $this;
    }
}

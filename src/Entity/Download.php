<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DownloadRepository;
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
 * @ORM\Entity(repositoryClass=DownloadRepository::class)
 */
class Download
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Package::class, inversedBy="downloads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $package;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $version;

    /**
     * @ORM\Column(type="datetime", name="installed_at")
     */
    private $installedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $composerVersion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phpVersion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPackage(): ?Package
    {
        return $this->package;
    }

    public function setPackage(?Package $package): self
    {
        $this->package = $package;

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

    public function getInstalledAt(): ?\DateTimeInterface
    {
        return $this->installedAt;
    }

    public function setInstalledAt(\DateTimeInterface $installedAt): self
    {
        $this->installedAt = $installedAt;

        return $this;
    }

    public function getComposerVersion(): ?string
    {
        return $this->composerVersion;
    }

    public function setComposerVersion(?string $composerVersion): self
    {
        $this->composerVersion = $composerVersion;

        return $this;
    }

    public function getPhpVersion(): ?string
    {
        return $this->phpVersion;
    }

    public function setPhpVersion(?string $phpVersion): self
    {
        $this->phpVersion = $phpVersion;

        return $this;
    }
}

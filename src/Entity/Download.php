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
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Package::class, inversedBy="downloads")
     * @ORM\JoinColumn(nullable=false)
     */
    private Package $package;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $version;

    /**
     * @ORM\Column(type="datetime", name="installed_at")
     */
    private \DateTimeInterface $installedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $composerVersion = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phpVersion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPackage(): Package
    {
        return $this->package;
    }

    public function setPackage(Package $package): self
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

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VersionRepository")
 */
class Version
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $version;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $homepage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $license;

    /**
     * @ORM\Column(type="json_array")
     */
    private $authors = [];

    /**
     * @ORM\Column(type="json_array")
     */
    private $extra = [];

    /**
     * @ORM\Column(type="json_array")
     */
    private $requireSection = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string|null
     */
    private $changelog;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $releaseDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Package", inversedBy="versions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $package;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function getExtra(): ?array
    {
        return $this->extra;
    }

    public function setExtra(array $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    public function getRequireSection(): ?array
    {
        return $this->requireSection;
    }

    public function setRequireSection(array $requireSection): self
    {
        $this->requireSection = $requireSection;

        return $this;
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

    /**
     * @return mixed
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    public function setHomepage($homepage): void
    {
        $this->homepage = $homepage;
    }

    public function getLicense()
    {
        return $this->license;
    }

    public function setLicense($license): void
    {
        $this->license = $license;
    }

    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function setAuthors(array $authors): void
    {
        $this->authors = $authors;
    }

    public function getChangelog(): ?string
    {
        return $this->changelog;
    }

    public function setChangelog(?string $changelog): void
    {
        $this->changelog = $changelog;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeInterface $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    public function toJson(): array
    {
        return [
            'version' => $this->version,
            'type' => $this->type,
            'description' => $this->description,
            'require' => $this->requireSection,
            'extra' => $this->extra,
            'dist' => [
                'url' => 'foo',
                'type' => 'zip',
            ],
        ];
    }
}

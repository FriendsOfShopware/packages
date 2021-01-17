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
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $binaryId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $version;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $homepage = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $license = null;

    /**
     * @var array<array{'name': string}>
     * @ORM\Column(type="json")
     */
    private array $authors = [];

    /**
     * @var array<string, string>
     * @ORM\Column(type="json")
     */
    private array $extra = [];

    /**
     * @var array<string, string>
     * @ORM\Column(type="json")
     */
    private array $requireSection = [];

    /**
     * @var array<string, string[]>
     * @ORM\Column(type="json")
     */
    private array $autoload = [];

    /**
     * @var array<string, string[]>
     * @ORM\Column(name="autoload_dev", type="json")
     */
    private array $autoloadDev = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $changelog = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $releaseDate = null;

    /**
     * @var array<string, mixed>
     * @ORM\Column(name="composer_json", type="json", nullable=true)
     */
    private ?array $composerJson = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Package", inversedBy="versions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Package $package;

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

    /**
     * @return array<string, string>|null
     */
    public function getExtra(): ?array
    {
        return $this->extra;
    }

    /**
     * @param array<string, string> $extra
     */
    public function setExtra(array $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * @return array<string, string>|null
     */
    public function getRequireSection(): ?array
    {
        return $this->requireSection;
    }

    /**
     * @param array<string, string> $requireSection
     *
     * @return $this
     */
    public function setRequireSection(array $requireSection): self
    {
        $this->requireSection = $requireSection;

        return $this;
    }

    public function addRequire(string $package, string $versionConstraint): self
    {
        $this->requireSection[$package] = $versionConstraint;

        return $this;
    }

    public function getPackage(): ?Package
    {
        return $this->package;
    }

    public function setPackage(Package $package): self
    {
        $this->package = $package;

        return $this;
    }

    public function getHomepage(): ?string
    {
        return $this->homepage;
    }

    public function setHomepage(?string $homepage): void
    {
        $this->homepage = $homepage;
    }

    public function getLicense(): string
    {
        return $this->license;
    }

    public function setLicense(string $license): void
    {
        $this->license = $license;
    }

    /**
     * @return array<array{'name': string}>
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param array<array{'name': string}> $authors
     */
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

    /**
     * @return array<string, string[]>
     */
    public function getAutoload(): array
    {
        return $this->autoload;
    }

    /**
     * @param array<string, string[]> $autoload
     */
    public function setAutoload(array $autoload): void
    {
        $this->autoload = $autoload;
    }

    /**
     * @return array<string, string[]>
     */
    public function getAutoloadDev(): array
    {
        return $this->autoloadDev;
    }

    /**
     * @param array<string, string[]> $autoloadDev
     */
    public function setAutoloadDev(array $autoloadDev): void
    {
        $this->autoloadDev = $autoloadDev;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getComposerJson(): ?array
    {
        return $this->composerJson;
    }

    /**
     * @param array<string, mixed>|null $composerJson
     */
    public function setComposerJson(?array $composerJson): void
    {
        $this->composerJson = $composerJson;
    }

    public function getBinaryId(): int
    {
        return $this->binaryId;
    }

    public function setBinaryId(int $binaryId): void
    {
        $this->binaryId = $binaryId;
    }

    /**
     * @return array<string, mixed>
     */
    public function toJson(): array
    {
        $json = [
            'version' => $this->version,
            'type' => $this->type,
            'autoload' => $this->autoload,
            'autoload-dev' => $this->autoloadDev,
            'description' => $this->description,
            'require' => $this->requireSection,
            'authors' => $this->authors,
            'extra' => $this->extra,
            'dist' => [
                'url' => 'foo',
                'type' => 'zip',
            ],
        ];

        if ($this->type === 'shopware-platform-plugin') {
            $json['replace'][$this->getComposerJson()['name']] = 'self.version';
        }

        return $json;
    }
}

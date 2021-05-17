<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PackageRepository")
 */
class Package
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $label;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $shortDescription = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $storeLink = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $releaseDate = null;

    /**
     * @var Collection<int, Version>
     * @ORM\OneToMany(targetEntity="App\Entity\Version", mappedBy="package", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $versions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Producer", inversedBy="package")
     * @ORM\JoinColumn(nullable=false)
     */
    private Producer $producer;

    /**
     * @var Collection<int, Download>
     * @ORM\OneToMany(targetEntity=Download::class, mappedBy="package", orphanRemoval=true)
     */
    private Collection $downloads;

    public function __construct()
    {
        $this->versions = new ArrayCollection();
        $this->downloads = new ArrayCollection();
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

    public function getComposerName(): string
    {
        return 'store.shopware.com/' . strtolower($this->name);
    }

    public function getCurrentVersion(): Version
    {
        $currentVersion = new Version();
        $currentVersion->setVersion('0.0.0');

        /** @var Version $version */
        foreach ($this->versions as $version) {
            if (version_compare((string) $version->getVersion(), (string) $currentVersion->getVersion(), '>')) {
                $currentVersion = $version;
            }
        }

        return $currentVersion;
    }

    /**
     * @param Collection<int, Version> $versions
     */
    public function setVersions(Collection $versions): void
    {
        $this->versions = $versions;
    }

    /**
     * @return Collection<int, Version>
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(Version $version): self
    {
        if (!$this->versions->contains($version)) {
            $this->versions[] = $version;
            $version->setPackage($this);
        }

        return $this;
    }

    public function removeVersion(Version $version): self
    {
        if ($this->versions->contains($version)) {
            $this->versions->removeElement($version);
        }

        return $this;
    }

    public function getProducer(): Producer
    {
        return $this->producer;
    }

    public function setProducer(Producer $producer): self
    {
        $this->producer = $producer;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getSafeDescription(): ?string
    {
        if (null === $this->description) {
            return null;
        }

        $text = strip_tags($this->description, '<h2><h3><h4><h5><h6><p><b><strong><li><ol><ul><br><i><a><style>');
        $text = preg_replace('/style=\\"[^\\"]*\\"/', '', (string) $text);
        $text = preg_replace('/class=\\"[^\\"]*\\"/', '', (string) $text);

        $followList = [
            getenv('APP_URL'),
        ];

        return preg_replace(
            '%(<a\s*(?!.*\brel=)[^>]*)(href="https?://)((?!(?:(?:www\.)?' . implode('|(?:www\.)?', $followList) . '))[^"]+)"((?!.*\brel=)[^>]*)(?:[^>]*)>%',
            '$1$2$3"$4 rel="noopener noreferrer">',
            (string) $text
        );
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    public function getStoreLink(): ?string
    {
        return $this->storeLink;
    }

    public function setStoreLink(?string $storeLink): void
    {
        $this->storeLink = $storeLink;
    }

    public function getReleaseDate(): ?DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?DateTimeInterface $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * @return Collection<int, Download>
     */
    public function getDownloads(): Collection
    {
        return $this->downloads;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }
}

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
     * @ORM\Column(type="json_array")
     */
    private $extra = [];

    /**
     * @ORM\Column(type="json_array")
     */
    private $requireSection = [];

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

    public function toJson(): array
    {
        return [
            'version' => $this->version,
            'description' => $this->description,
            'require' => $this->requireSection,
            'extra' => $this->extra,
            'dist' => [
                'url' => 'foo',
                'type' => 'zip'
            ]
        ];
    }
}

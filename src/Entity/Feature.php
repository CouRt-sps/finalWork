<?php

namespace App\Entity;

use App\Repository\FeatureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FeatureRepository::class)
 */
class Feature
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Rating;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Matrix;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $Headphones;

    /**
     * @ORM\Column(type="integer")
     */
    private $WorkingHours;

    /**
     * @ORM\OneToOne(targetEntity=Products::class, inversedBy="feature", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?int
    {
        return $this->Rating;
    }

    public function setRating(?int $Rating): self
    {
        $this->Rating = $Rating;

        return $this;
    }

    public function getMatrix(): ?string
    {
        return $this->Matrix;
    }

    public function setMatrix(string $Matrix): self
    {
        $this->Matrix = $Matrix;

        return $this;
    }

    public function getHeadphones(): ?bool
    {
        return $this->Headphones;
    }

    public function setHeadphones(?bool $Headphones): self
    {
        $this->Headphones = $Headphones;

        return $this;
    }

    public function getWorkingHours(): ?int
    {
        return $this->WorkingHours;
    }

    public function setWorkingHours(int $WorkingHours): self
    {
        $this->WorkingHours = $WorkingHours;

        return $this;
    }

    public function getProduct(): ?Products
    {
        return $this->product;
    }

    public function setProduct(Products $product): self
    {
        $this->product = $product;

        return $this;
    }
}

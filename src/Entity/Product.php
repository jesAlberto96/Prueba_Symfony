<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $code;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $brand;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    public $categoria;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //CODE
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'code',
            'message' => 'El código ya han sido usandos.',
        ]));
        $metadata->addPropertyConstraint('code', new Assert\NotBlank());
        $metadata->addPropertyConstraint('code', new Assert\Range([
            'min' => 4,
            'max' => 10,
            'notInRangeMessage' => 'El código debe tener mínimo {{ min }} caracteres y máximo {{ max }} caracteres',
        ]));
        //ENDCODE

        //NAME
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addConstraint(new UniqueEntity([
            'fields' => 'name',
            'message' => 'El nombre ya ha sido usando.',
        ]));
        //ENDNAME

        //DESCRIPTION
        $metadata->addPropertyConstraint('description', new Assert\NotBlank());

        //BRAND
        $metadata->addPropertyConstraint('brand', new Assert\NotBlank());

        //PRICE
        $metadata->addPropertyConstraint('price', new Assert\NotBlank());

        //CATEGORIA
        $metadata->addPropertyConstraint('categoria', new Assert\NotBlank());
    }
}

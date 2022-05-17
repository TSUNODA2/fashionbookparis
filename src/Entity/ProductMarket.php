<?php

namespace App\Entity;

use App\Repository\ProductMarketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductMarketRepository::class)]
class ProductMarket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $product_Namet;

    #[ORM\Column(type: 'text')]
    private $product_description;

    #[ORM\Column(type: 'float')]
    private $product_price;

    #[ORM\Column(type: 'string', length: 255)]
    private $product_picture;

    #[ORM\Column(type: 'string', length: 255)]
    private $product_slug;

    #[ORM\ManyToOne(targetEntity: ProductCategory::class, inversedBy: 'products')]
    private $productCategory;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductNamet(): ?string
    {
        return $this->product_Namet;
    }

    public function setProductNamet(string $product_Namet): self
    {
        $this->product_Namet = $product_Namet;

        return $this;
    }

    public function getProductDescription(): ?string
    {
        return $this->product_description;
    }

    public function setProductDescription(string $product_description): self
    {
        $this->product_description = $product_description;

        return $this;
    }

    public function getProductPrice(): ?float
    {
        return $this->product_price;
    }

    public function setProductPrice(float $product_price): self
    {
        $this->product_price = $product_price;

        return $this;
    }

    public function getProductPicture(): ?string
    {
        return $this->product_picture;
    }

    public function setProductPicture(string $product_picture): self
    {
        $this->product_picture = $product_picture;

        return $this;
    }

    public function getProductSlug(): ?string
    {
        return $this->product_slug;
    }

    public function setProductSlug(string $product_slug): self
    {
        $this->product_slug = $product_slug;

        return $this;
    }

    public function getProductCategory(): ?ProductCategory
    {
        return $this->productCategory;
    }

    public function setProductCategory(?ProductCategory $productCategory): self
    {
        $this->productCategory = $productCategory;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}

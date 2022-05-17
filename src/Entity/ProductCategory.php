<?php

namespace App\Entity;

use App\Repository\ProductCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductCategoryRepository::class)]
class ProductCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name_category;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug_category;

    #[ORM\OneToMany(mappedBy: 'productCategory', targetEntity: ProductMarket::class)]
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCategory(): ?string
    {
        return $this->name_category;
    }

    public function setNameCategory(string $name_category): self
    {
        $this->name_category = $name_category;

        return $this;
    }

    public function getSlugCategory(): ?string
    {
        return $this->slug_category;
    }

    public function setSlugCategory(string $slug_category): self
    {
        $this->slug_category = $slug_category;

        return $this;
    }

    /**
     * @return Collection<int, ProductMarket>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(ProductMarket $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setProductCategory($this);
        }

        return $this;
    }

    public function removeProduct(ProductMarket $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getProductCategory() === $this) {
                $product->setProductCategory(null);
            }
        }

        return $this;
    }
}

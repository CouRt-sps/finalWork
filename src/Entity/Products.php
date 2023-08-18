<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 * @Assert\EnableAutoMapping()
 */
class Products
{
    use TimestampableEntity;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"product"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFilename;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Price::class, mappedBy="product", orphanRemoval=true, fetch="EXTRA_LAZY")
     */
    private $prices;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="product", orphanRemoval=true, fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity=Discounts::class, inversedBy="product")
     * @ORM\JoinColumn(nullable=true)
     */
    private $discounts;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantityBuy;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $limitedEdition;

    /**
     * @ORM\Column(type="integer")
     */
    private $sortIndex;

    /**
     * @ORM\OneToMany(targetEntity=ViewHistory::class, mappedBy="product")
     */
    private $viewHistories;
    
    /**
     * @ORM\OneToOne(targetEntity=Feature::class, mappedBy="product", cascade={"persist", "remove"})
     */
    private $feature;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="product", orphanRemoval=true)
     */
    private $cartItems;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->viewHistories = new ArrayCollection();
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

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

    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
            $price->setProduct($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getProduct() === $this) {
                $price->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    public function getDiscounts(): ?Discounts
    {
        return $this->discounts;
    }

    public function setDiscounts(?Discounts $discounts): self
    {
        $this->discounts = $discounts;

        return $this;
    }

    public function getQuantityBuy(): ?int
    {
        return $this->quantityBuy;
    }

    public function setQuantityBuy(int $quantityBuy): self
    {
        $this->quantityBuy = $quantityBuy;

        return $this;
    }

    public function getLimitedEdition(): ?bool
    {
        return $this->limitedEdition;
    }

    public function setLimitedEdition(?bool $limitedEdition): self
    {
        $this->limitedEdition = $limitedEdition;

        return $this;
    }

    public function getSortIndex(): ?int
    {
        return $this->sortIndex;
    }

    public function setSortIndex(int $sortIndex): self
    {
        $this->sortIndex = $sortIndex;

        return $this;
    }

    /**
     * @return Collection<int, ViewHistory>
     */
    public function getViewHistories(): Collection
    {
        return $this->viewHistories;
    }

    public function addViewHistory(ViewHistory $viewHistory): self
    {
        if (!$this->viewHistories->contains($viewHistory)) {
            $this->viewHistories[] = $viewHistory;
            $viewHistory->setProduct($this);
        }

        return $this;
    }

    public function removeViewHistory(ViewHistory $viewHistory): self
    {
        if ($this->viewHistories->removeElement($viewHistory)) {
            // set the owning side to null (unless already changed)
            if ($viewHistory->getProduct() === $this) {
                $viewHistory->setProduct(null);
            }
        }

        return $this;
    }

    public function getFeature(): ?Feature
    {
        return $this->feature;
    }

    public function setFeature(Feature $feature): self
    {
        // set the owning side of the relation if necessary
        if ($feature->getProduct() !== $this) {
            $feature->setProduct($this);
        }

        $this->feature = $feature;

        return $this;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems[] = $cartItem;
            $cartItem->setProduct($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getProduct() === $this) {
                $cartItem->setProduct(null);
            }
        }

        return $this;
    }
}

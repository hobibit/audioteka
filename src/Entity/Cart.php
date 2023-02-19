<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class Cart implements \App\Service\Cart\Cart
{
    public const CAPACITY = 3;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', nullable: false)]
    private UuidInterface $id;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartProducts::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $cartProducts;

    public function __construct(string $id)
    {
        $this->id = Uuid::fromString($id);
        $this->cartProducts = new ArrayCollection();
    }

    public function __get($name)
    {
        if ($name === 'products') {
            return $this->getProducts();
        }

        return;
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getTotalPrice(): int
    {
        return array_reduce(
            $this->cartProducts->toArray(),
            static fn(int $total, CartProducts $cartProduct): int => $total + $cartProduct->getProduct()?->getPrice(),
            0
        );
    }

    public function isFull(): bool
    {
        return $this->cartProducts->count() >= self::CAPACITY;
    }

    public function getProducts(): iterable
    {
        $products = new ArrayCollection();
        $cartProducts = $this->cartProducts->toArray();
        array_walk($cartProducts, static function($cartProduct) use ($products): void {
            $products->add($cartProduct->getProduct());
        });

        return $products;
    }

    #[Pure]
    public function hasProduct(Product $product): bool
    {
        foreach ($this->cartProducts->toArray() as $cartProduct) {
            if ($cartProduct->getProduct() === $product) {
                return true;
            }
        }

        return false;
    }

    public function addProduct(Product $product): void
    {
        if ($this->isFull()) {
            return;
        }

        $this->cartProducts->add(new CartProducts($this, $product));
    }

    public function removeProduct(Product $product): void
    {
        foreach ($this->cartProducts->toArray() as $cartProduct) {
            if ($cartProduct->getProduct() === $product) {
                $this->removeCartProduct($cartProduct);
                break;
            }
        }
    }

    public function getProductsCount(): int
    {
        return $this->cartProducts->count();
    }

    /**
     * @return Collection<int, CartProducts>
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function addCartProduct(CartProducts $cartProduct): self
    {
        if (!$this->cartProducts->contains($cartProduct)) {
            $this->cartProducts->add($cartProduct);
            $cartProduct->setCart($this);
        }

        return $this;
    }

    public function removeCartProduct(CartProducts $cartProduct): self
    {
        if ($this->cartProducts->removeElement($cartProduct) && $cartProduct->getCart() === $this) {
            $cartProduct->setCart(null);
        }

        return $this;
    }
}

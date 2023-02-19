<?php

declare(strict_types=1);

namespace App\Tests\Unit\ResponseBuilder;

use App\Entity\Cart;
use App\Entity\Product;
use App\ResponseBuilder\CartBuilder;
use PHPUnit\Framework\TestCase;

class CartLimitTest extends TestCase
{
    public function testThatCartCountCannotExceedMaxCapacity(): void
    {
        $cart = new Cart('c3cec582-30ab-4087-ad09-a85ee41d765b');
        $cart->addProduct(new Product('f9a4e2dc-0094-4534-b7fd-73ed6dcbb253', 'Kosmici, którzy kradną mi skarpetki', 1000));
        $cart->addProduct(new Product('e0711641-01c4-4044-b584-e005f1ff80be', 'Jak uczyć chomika języka migowego', 1000));
        $cart->addProduct(new Product('9b8dd514-31f8-49bc-b9c4-b9c516b42d60', 'Jak zrobić wiatraczek z liści szpinaku', 1000));
        $cart->addProduct(new Product('d405c93a-013f-48bc-84cd-0fe4b2992c9f', 'Poradnik dla samotnych mrówek: Jak znaleźć miłość wśród milionów', 1000));

        $this->assertEquals(3, $cart->getProductsCount());
    }
}
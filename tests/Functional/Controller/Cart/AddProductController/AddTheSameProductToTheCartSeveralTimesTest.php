<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Cart\AddProductController;

use App\Tests\Functional\WebTestCase;

class AddTheSameProductToTheCartSeveralTimesTest extends WebTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures(new AddProductControllerFixture());
    }

    public function testThatUserCanAddSameProductToCartSeveralTimes(): void
    {
        //given
        $this->client->request('PUT', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        $this->client->request('PUT', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');

        //when
        $this->client->request('GET', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c');

        //then
        $response = $this->getJsonResponse();
        self::assertCount(2, $response['products']);
    }

    public function testThatUserCanAddSameProductToCartSeveralTimesAndThenRemoveOneNotAll(): void
    {
        //given
        $this->client->request('PUT', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        $this->client->request('PUT', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        $this->client->request('PUT', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');

        //when
        $this->client->request('DELETE', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        $this->client->request('GET', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c');

        //then
        $response = $this->getJsonResponse();
        self::assertCount(2, $response['products']);
    }

    public function testThatPriceIsCalculatedProperlyWithMultipleSameProducts(): void
    {
        //given
        $this->client->request('PUT', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        $this->client->request('PUT', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        $this->client->request('PUT', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');

        //when
        $this->client->request('DELETE', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c/fbcb8c51-5dcc-4fd4-a4cd-ceb9b400bff7');
        $this->client->request('GET', '/cart/5bd88887-7017-4c08-83de-8b5d9abde58c');

        //then
        $response = $this->getJsonResponse();
        self::assertEquals(3980, $response['total_price']);
    }
}
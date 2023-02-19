<?php

namespace App\Messenger;

use App\Service\Catalog\ProductService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EditProductHandler implements MessageHandlerInterface
{
    public function __construct(private ProductService $service) { }

    public function __invoke(EditProduct $command): void
    {
        $this->service->edit($command->productId, $command->name, $command->price);
    }
}
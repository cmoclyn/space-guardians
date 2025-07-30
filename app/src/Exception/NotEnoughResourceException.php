<?php

namespace App\Exception;

use App\Entity\Resource;
use Exception;
use Throwable;

class NotEnoughResourceException extends Exception
{
    public function __construct(
        private readonly Resource $resource,
        int $expectedQuantity,
        int $actualQuantity,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                'There is not enough %s (%d expected / %d actual).',
                $this->resource->getName(),
                $expectedQuantity,
                $actualQuantity,
            ),
            500,
            $previous,
        );
    }
}
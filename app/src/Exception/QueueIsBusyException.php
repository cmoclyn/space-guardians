<?php

namespace App\Exception;

use App\Entity\Planet;
use Exception;
use Throwable;

class QueueIsBusyException extends Exception
{
    public function __construct(
        private readonly Planet $planet,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            sprintf(
                'There is already something currently building on %s.',
                $this->planet->getName(),
            ),
            500,
            $previous,
        );
    }
}
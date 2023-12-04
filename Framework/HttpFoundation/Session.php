<?php

declare(strict_types=1);

namespace Framework\HttpFoundation;

final class Session extends Parameter
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(private array $parameters)
    {
        parent::__construct($parameters);
    }

    public function getFlashBag(): FlashBag
    {
        return new FlashBag();
    }
}

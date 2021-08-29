<?php

namespace Raigu\TestDouble\Psr20;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

/**
 * I am a system clock
 */
final class DefaultClock implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable;
    }
}
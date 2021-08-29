<?php

namespace Raigu\TestDouble\Psr20;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

final class FrozenClock implements ClockInterface
{
    private DateTimeImmutable $now;

    public function now(): DateTimeImmutable
    {
        return $this->now;
    }

    public function __construct(\DateTimeInterface $dateTime = null)
    {
        $dateTime = $dateTime ?? new DateTimeImmutable;
        $this->now = ($dateTime instanceof \DateTime) ? DateTimeImmutable::createFromMutable($dateTime) : $dateTime;
    }
}
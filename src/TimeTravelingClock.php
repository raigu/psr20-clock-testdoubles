<?php

namespace Raigu\TestDouble\Psr20;

use DateInterval;
use DateTimeImmutable;
use Psr\Clock\ClockInterface;

/**
 * I am time traveling clock.
 * I keep the time shift and add or subtract it behind the scenes.
 */
final class TimeTravelingClock implements ClockInterface
{

    private DateInterval $shift;
    private ClockInterface $clock;

    public function now(): DateTimeImmutable
    {
        return $this->clock->now()->add($this->shift);
    }

    public function travelInTime(DateTimeImmutable $dateTime)
    {
        $this->shift = $this->clock->now()->diff($dateTime);
    }

    public function travelInTimeByInterval(DateInterval $interval): void
    {
        $moment = $this->clock->now();
        $this->shift = $moment->diff($moment->add($this->shift)->add($interval));
    }

    public function __construct(ClockInterface $clock = null)
    {
        $this->shift = new DateInterval('PT0S');
        $this->clock = $clock ?? new DefaultClock;
    }
}
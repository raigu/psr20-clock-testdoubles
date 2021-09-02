<?php

namespace Raigu\TestDouble\Psr20;

use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Raigu\TestDouble\Psr20\FrozenClock
 */
final class FrozenClockTest extends TestCase
{
    /**
     * @test
     */
    public function returns_always_same_time(): void
    {
        $sut = new FrozenClock;
        $first = $sut->now()->getTimestamp();
        sleep(1);
        $second = $sut->now()->getTimestamp();
        $this->assertEquals($first, $second);
    }

    /**
     * @test
     */
    public function datetime_can_be_given(): void
    {
        $moment = \DateTimeImmutable::createFromFormat(DATE_ATOM, '2021-01-02T03:15:45-07:00');
        assert($moment !== false);
        $sut = new FrozenClock($moment);
        sleep(1);
        $this->assertEquals($moment->getTimestamp(), $sut->now()->getTimestamp());
    }

    /**
     * @test
     */
    public function given_datetime_is_handled_as_immutable(): void
    {
        $mutableMoment = new DateTime;
        $expected = $mutableMoment->getTimestamp();
        $sut = new FrozenClock($mutableMoment);

        $mutableMoment->sub(new \DateInterval('P1D'));
        $this->assertEquals(
            $expected,
            $sut->now()->getTimestamp()
        );
    }
}
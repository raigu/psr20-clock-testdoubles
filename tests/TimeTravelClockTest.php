<?php

namespace Raigu\TestDouble\Psr20;

use DateTimeImmutable;

/**
 * @covers \Raigu\TestDouble\Psr20\TimeTravelClock
 * @uses \Raigu\TestDouble\Psr20\FrozenClock
 * @uses \Raigu\TestDouble\Psr20\DefaultClock
 */
final class TimeTravelClockTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test
     */
    public function by_default_uses_system_clock_as_base_clock(): void
    {
        $sut = new TimeTravelClock;
        $first = $sut->now()->getTimestamp();
        sleep(1);
        $second = $sut->now()->getTimestamp();

        $this->assertEquals($first + 1, $second);
    }

    /**
     * @test
     */
    public function clock_can_be_given(): void
    {
        $sut = new TimeTravelClock(
            new FrozenClock(
                $moment = new DateTimeImmutable('2500-01-02')
            )
        );
        $this->assertEquals(
            $moment->getTimestamp(),
            $sut->now()->getTimestamp()
        );
    }

    /**
     * @test
     */
    public function travels_in_time_to_the_future(): void
    {
        $sut = new TimeTravelClock(new FrozenClock);
        $sut->travelInTime(
            $expected = new DateTimeImmutable('2500-01-02')
        );

        $this->assertEquals(
            $expected->getTimestamp(),
            $sut->now()->getTimestamp()
        );
    }

    /**
     * @test
     */
    public function travels_in_time_to_the_past(): void
    {
        $sut = new TimeTravelClock(new FrozenClock);
        $sut->travelInTime(
            $expected = new DateTimeImmutable('1979-01-02')
        );

        $this->assertEquals(
            $expected->getTimestamp(),
            $sut->now()->getTimestamp()
        );
    }

    /**
     * @test
     */
    public function travels_in_time_by_interval(): void
    {
        $sut = new TimeTravelClock(
            new FrozenClock(
                $moment = new DateTimeImmutable
            )
        );
        $sut->travelInTimeByInterval(new \DateInterval('P1D'));

        $this->assertEquals(
            $moment->add(new \DateInterval('P1D'))->getTimestamp(),
            $sut->now()->getTimestamp()
        );
    }

    /**
     * @test
     */
    public function can_travel_in_time_by_interval_multiple_times(): void
    {
        $sut = new TimeTravelClock(
            new FrozenClock(
                $moment = new DateTimeImmutable('2500-01-02')
            )
        );
        $sut->travelInTimeByInterval(new \DateInterval('P1D'));
        $sut->travelInTimeByInterval(new \DateInterval('P1D'));

        $this->assertEquals(
            $moment->add(new \DateInterval('P2D'))->getTimestamp(),
            $sut->now()->getTimestamp()
        );
    }

    /**
     * @test
     */
    public function travels_in_time_by_interval_to_the_past(): void
    {
        $current = new DateTimeImmutable;
        $sut = new TimeTravelClock(
            new FrozenClock(
                $current
            )
        );
        $sut->travelInTimeByInterval(\DateInterval::createFromDateString('-1 day'));

        $this->assertEquals(
            $current->sub(new \DateInterval('P1D'))->getTimestamp(),
            $sut->now()->getTimestamp()
        );
    }
}
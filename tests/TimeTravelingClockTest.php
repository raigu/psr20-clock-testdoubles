<?php

namespace Raigu\TestDouble\Psr20;

/**
 * @covers \Raigu\TestDouble\Psr20\TimeTravelingClock
 */
final class TimeTravelingClockTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @test
     */
    public function by_default_uses_system_clock_as_base_clock()
    {
        $sut = new TimeTravelingClock;
        $first = $sut->now()->getTimestamp();
        sleep(1);
        $second = $sut->now()->getTimestamp();

        $this->assertEquals($first + 1, $second);
    }

    /**
     * @test
     */
    public function clock_can_be_given()
    {
        $sut = new TimeTravelingClock(
            new FrozenClock(
                $moment = \DateTimeImmutable::createFromFormat(DATE_ATOM, '2500-01-02T03:15:45-07:00')
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
    public function travels_in_time_to_the_future()
    {
        $sut = new TimeTravelingClock(new FrozenClock);
        $sut->travelInTime(
            $expected = \DateTimeImmutable::createFromFormat(DATE_ATOM, '2500-01-02T03:15:45-07:00')
        );

        $this->assertEquals(
            $expected->getTimestamp(),
            $sut->now()->getTimestamp()
        );
    }

    /**
     * @test
     */
    public function travels_in_time_to_the_past()
    {
        $sut = new TimeTravelingClock(new FrozenClock);
        $sut->travelInTime(
            $expected = \DateTimeImmutable::createFromFormat(DATE_ATOM, '1979-01-02T03:15:45-07:00')
        );

        $this->assertEquals(
            $expected->getTimestamp(),
            $sut->now()->getTimestamp()
        );
    }

    /**
     * @test
     */
    public function travels_in_time_by_interval()
    {
        $sut = new TimeTravelingClock(
            new FrozenClock(
                $moment = \DateTimeImmutable::createFromFormat(DATE_ATOM, '2500-01-02T03:15:45-07:00')
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
    public function can_travel_in_time_by_interval_multiple_times()
    {
        $sut = new TimeTravelingClock(
            new FrozenClock(
                $moment = \DateTimeImmutable::createFromFormat(DATE_ATOM, '2500-01-02T03:15:45-07:00')
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
    public function travels_in_time_by_interval_to_the_past()
    {
        $current = new \DateTimeImmutable;
        $sut = new TimeTravelingClock(
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
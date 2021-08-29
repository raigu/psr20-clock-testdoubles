<?php

namespace Raigu\TestDouble\Psr20;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Raigu\TestDouble\Psr20\DefaultClock
 */
final class DefaultClockTest extends TestCase
{
    /**
     * @test
     */
    public function returns_current_system_time()
    {
        $sut = new DefaultClock;
        $this->assertEquals(
            (new \DateTimeImmutable)->getTimestamp(),
            $sut->now()->getTimestamp()
        );
        sleep(1);
        $this->assertEquals(
            (new \DateTimeImmutable)->getTimestamp(),
            $sut->now()->getTimestamp()
        );
    }
}
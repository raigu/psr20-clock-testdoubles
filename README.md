[![Latest Stable Version](http://poser.pugx.org/raigu/psr20-clock-testdoubles/v)](https://packagist.org/packages/raigu/psr20-clock-testdoubles)
[![Latest Unstable Version](http://poser.pugx.org/raigu/psr20-clock-testdoubles/v/unstable)](https://packagist.org/packages/raigu/psr20-clock-testdoubles)
[![Fallows SemVer](https://img.shields.io/badge/SemVer-2.0.0-green)](https://semver.org/spec/v2.0.0.html)
[![build](https://github.com/raigu/psr20-clock-testdoubles/workflows/build/badge.svg)](https://github.com/raigu/psr20-clock-testdoubles/actions)
[![codecov](https://codecov.io/gh/raigu/psr20-clock-testdoubles/branch/main/graph/badge.svg?token=9DD044TN72)](https://codecov.io/gh/raigu/psr20-clock-testdoubles)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fraigu%2Fpsr20-clock-testdoubles%2Fmain)](https://dashboard.stryker-mutator.io/reports/github.com/raigu/psr20-clock-testdoubles/main)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)


Test Doubles for PSR-20 clock

Unstable because the PSR-20 is not officially released yet.

# Compatibility

* PHP 7.4, ^8.0
* psr/clock==@dev

# Changes

[./CHANGELOG.md](./CHANGELOG.md)

# Install

```shell
$ composer require --dev raigu/psr20-clock-testdoubles
```

# Test Doubles

## FrozenClock

Clock frozen in time. It always returns the same time now matter how many times the `now` is called.

```php
// creating with current system date and time
$clock = new \Raigu\TestDouble\Psr20\FrozenClock;

// creating with predefined date and time at
$clock = new \Raigu\TestDouble\Psr20\FrozenClock(
    new DateTimeImmutable('2020-01-02')
);
```

## TimeTravelClock

Clock that acts like normal clock but has a shift in time if given.

```php
// By default, acts like normal clock. 
$clock = new \Raigu\TestDouble\Psr20\TimeTravelClock();
$moment = $clock->now();
sleep(2);
// after two seconds the TimeTravelClock is also moved forward.
assert($moment->add(new DateInterval('PT2S'))->getTimestamp() === $clock->now()->getTimestamp());

// Moving to the specific date and time in the future or past:
$clock->travelInTime(new DateTimeImmutable('2020-01-02'));
assert($clock->now()->format('Y-m-d') === '2020-01-02');

// Move by the specific interval to the future
$clock->travelInTimeByInterval(new DateInterval('P10D'));
assert($clock->now()->format('Y-m-d') === '2020-01-12');

// Move by the specific interval to the past
$clock->travelInTimeByInterval(DateInterval::createFromDateString('-1 day'));
assert($clock->now()->format('Y-m-d') === '2020-01-11');
```

The `TimeTravelClock` implements the Decorator pattern by wrapping 
a clock implementation. By default, it uses a system clock implementation, 
but it can be replaced in constructor.

For example, it is possible to create TimeTravelClock that is frozen by using `FrozenClock`:

```php
$clock = new \Raigu\TestDouble\Psr20\TimeTravelClock(
    new Raigu\TestDouble\Psr20\FrozenClock()
);
$moment = $clock->now();
sleep(2);
assert($moment->getTimestamp() === $clock->getTimestamp(), 'Does not tick because base clock is frozen.');
```

# Testing

```shell
$ composer test
$ composer specification 
$ composer coverage
```

[![Latest Stable Version](http://poser.pugx.org/raigu/psr20-clock-testdoubles/v)](https://packagist.org/packages/raigu/psr20-clock-testdoubles)
[![Latest Unstable Version](http://poser.pugx.org/raigu/psr20-clock-testdoubles/v/unstable)](https://packagist.org/packages/raigu/psr20-clock-testdoubles)
[![Fallows SemVer](https://img.shields.io/badge/SemVer-2.0.0-green)](https://semver.org/spec/v2.0.0.html)
[![build](https://github.com/raigu/psr20-clock-testdoubles/workflows/build/badge.svg)](https://github.com/raigu/psr20-clock-testdoubles/actions)
[![codecov](https://codecov.io/gh/raigu/psr20-clock-testdoubles/branch/main/graph/badge.svg?token=9DD044TN72)](https://codecov.io/gh/raigu/psr20-clock-testdoubles)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)


# psr20-clock-testdoubles

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

Clock frozen in time.

```php
$clock = new \Raigu\TestDouble\Psr20\FrozenClock;
$moment = $clock->now();
sleep(2);

assert($moment->getTimestamp() === $clock->now()->getTimestamp());
```

The time can be given by constructor:

```php
$moment = \DateTimeImmutable::createFromFormat('Y-m-d', '2020-01-02');
$clock = new \Raigu\TestDouble\Psr20\FrozenClock($moment);

assert($moment->getTimestamp() === $clock->now()->getTimestamp());
```

## TimeTravellingClock


```php
$clock = new \Raigu\TestDouble\Psr20\TimeTravelingClock();
```

By default, it acts as normal clock by moving in time:

```php
$moment = $clock->now();
sleep(2);
assert($moment->add(new DateInterval('PT2S'))->getTimestamp() === $clock->now()->getTimestamp());
```

Moving to the specific date and time in future or past:
```php
$clock->travelInTime(
    \DateTimeImmutable::createFromFormat('Y-m-d', '2020-01-02')
);
assert($clock->now()->format('Y-m-d') === '2020-01-02');
```

It is possible to move by interval:

```php
// Move by the specific interval
$clock->travelInTimeByInterval(new DateInterval('P10D'));
assert($clock->now()->format('Y-m-d') === '2020-01-12');
```

The `TimeTravellingClock` depends on an internal clock, which can be replaced by any clock.
For example by `FrozenClock`:

```php
$clock = new \Raigu\TestDouble\Psr20\TimeTravelingClock(
    new Raigu\TestDouble\Psr20\FrozenClock
);
$moment = $clock->now();
sleep(2);
assert($moment->getTimestamp() === $clock->getTimestamp(), 'Because base clock is frozen the time travling clock does not tick');
```

# Testing

```shell
$ composer test
$ composer specification 
$ composer coverage
```

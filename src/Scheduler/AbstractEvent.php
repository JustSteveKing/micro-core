<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Scheduler;

use Carbon\Carbon;
use Cron\CronExpression;
use JustSteveKing\Micro\Contracts\ScheduledEventContract;
use JustSteveKing\Micro\Scheduler\Concerns\HasFrequency;

abstract class AbstractEvent implements ScheduledEventContract
{
    use HasFrequency;

    public const MONDAY = '1';
    public const TUESDAY = '2';
    public const WEDNESDAY = '3';
    public const THURSDAY = '4';
    public const FRIDAY = '5';
    public const SATURDAY = '6';
    public const SUNDAY = '7';

    public string $expression = '* * * * *';

    public function dueToRun(Carbon $date = null): bool
    {
        return (new CronExpression(
            expression: $this->expression,
        ))->isDue(
            currentTime: $date ?? Carbon::now(),
        );
    }
}
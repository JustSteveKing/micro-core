<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Scheduler\Concerns;

use JustSteveKing\Micro\Scheduler\AbstractEvent;
use JustSteveKing\Micro\Scheduler\Event;

trait HasFrequency
{
    /**
     * Set the CRON expression
     *
     * @param string $expression
     *
     * @return $this
     */
    public function cron(string $expression): self
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * Set the CRON expression to be every minute.
     *
     * @return $this
     */
    public function everyMinute(): self
    {
        return $this->cron(
            expression: '* * * * *',
        );
    }

    /**
     * Set the CRON expression to be every [$minutes] minute.
     *
     * @param int $minutes
     *
     * @return $this
     */
    public function everyMinutes(int $minutes): self
    {
        return $this->replaceIntoExpression(
            position: 1,
            segment: "*/{$minutes}",
        );
    }

    /**
     * Set the CRON expression to be every [$hours] hours.
     *
     * @param int $hours
     *
     * @return $this
     */
    public function everyHours(int $hours): self
    {
        return $this->replaceIntoExpression(
            position: 2,
            segment: "*/{$hours}",
        );
    }

    /**
     * Set the CRON expression to be every [$days] days.
     *
     * @param int $days
     *
     * @return $this
     */
    public function everyDays(int $days): self
    {
        return $this->replaceIntoExpression(
            position: 3,
            segment: "*/{$days}",
        );
    }

    /**
     * Set the CRON expression to be every [$months] months.
     *
     * @param int $months
     *
     * @return $this
     */
    public function everyMonths(int $months): self
    {
        return $this->replaceIntoExpression(
            position: 4,
            segment: "*/{$months}",
        );
    }

    /**
     * Set the CRON expression to be every [$weeks] weeks.
     *
     * @param int $weeks
     *
     * @return $this
     */
    public function everyWeeks(int $weeks): self
    {
        return $this->replaceIntoExpression(
            position: 5,
            segment: "*/{$weeks}",
        );
    }

    /**
     * Pass through a list of days to set on the CRON expression.
     *
     * @param string|int ...$days
     *
     * @return $this
     */
    public function days(string|int ...$days): self
    {
        $this->replaceIntoExpression(
            position: 5,
            segment: implode(',', func_get_args() ?: ['*']),
        );
        return $this;
    }

    /**
     * Set the CRON expression to be on Mondays.
     *
     * @return $this
     */
    public function mondays(): self
    {
        return $this->days(AbstractEvent::MONDAY);
    }

    /**
     * Set the CRON expression to be on Tuesdays.
     *
     * @return $this
     */
    public function tuesdays(): self
    {
        return $this->days(AbstractEvent::TUESDAY);
    }

    /**
     * Set the CRON expression to be on Wednesdays.
     *
     * @return $this
     */
    public function wednesdays(): self
    {
        return $this->days(AbstractEvent::WEDNESDAY);
    }

    /**
     * Set the CRON expression to be on Thursdays.
     *
     * @return $this
     */
    public function thursdays(): self
    {
        return $this->days(AbstractEvent::THURSDAY);
    }

    /**
     * Set the CRON expression to be on Fridays.
     *
     * @return $this
     */
    public function fridays(): self
    {
        return $this->days(AbstractEvent::FRIDAY);
    }

    /**
     * Set the CRON expression to be on Saturdays.
     *
     * @return $this
     */
    public function saturdays(): self
    {
        return $this->days(AbstractEvent::SATURDAY);
    }

    /**
     * Set the CRON expression to be on Sundays.
     *
     * @return $this
     */
    public function sundays(): self
    {
        return $this->days(AbstractEvent::SUNDAY);
    }

    /**
     * Set the CRON expression to be on Weekdays.
     *
     * @return $this
     */
    public function weekdays(): self
    {
        return $this->days(
            AbstractEvent::MONDAY,
            AbstractEvent::TUESDAY,
            AbstractEvent::WEDNESDAY,
            AbstractEvent::THURSDAY,
            AbstractEvent::FRIDAY,
        );
    }

    /**
     * Set the CRON expression to be on Weekends.
     *
     * @return $this
     */
    public function weekends(): self
    {
        return $this->days(
            AbstractEvent::SATURDAY, AbstractEvent::SUNDAY,
        );
    }

    /**
     * Set the CRON expression to be daily at a set time.
     *
     * @return $this
     */
    public function dailyAt(string|int $hour = 0, string|int $minute = 0): self
    {
        return $this->replaceIntoExpression(
            position: 1,
            segment: [$minute, $hour],
        );
    }

    /**
     * Set the CRON expressions to be daily.
     *
     * @return $this
     */
    public function daily(): self
    {
        return $this->dailyAt(
            hour: 0,
            minute: 0,
        );
    }

    public function twiceDaily(string|int $firstHour = 1, string|int $lastHour = 12): self
    {
        return $this->replaceIntoExpression(
            position: 1,
            segment: [0, "{$firstHour},{$lastHour}"],
        );
    }

    public function hourlyAt(string|int $minute = 1): self
    {
        return $this->replaceIntoExpression(
            position: 1,
            segment: (string) $minute,
        );
    }

    public function hourly(): self
    {
        return $this->hourlyAt(
            minute: 1,
        );
    }

    public function at(string|int $hour = 0, string|int $minute = 0): self
    {
        return $this->replaceIntoExpression(
            position: 1,
            segment: [$minute, $hour],
        );
    }

    public function monthly(): self
    {
        return $this->monthlyOn(
            date: 1,
        );
    }

    public function monthlyOn(string|int $date = 1): self
    {
        return $this->replaceIntoExpression(
            position: 1,
            segment: [0, 0, $date],
        );
    }

    public function replaceIntoExpression(int $position, array|string $segment): self
    {
        $segment = (array) $segment;

        $expression = explode(' ', $this->expression);

        array_splice(
            $expression,
            $position - 1,
            count($segment),
            $segment
        );

        $expression = array_slice($expression, 0, 5);

        return $this->cron(
            expression: implode(' ', $expression),
        );
    }
}
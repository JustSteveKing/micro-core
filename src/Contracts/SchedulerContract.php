<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Contracts;

use Carbon\Carbon;
use RuntimeException;

interface SchedulerContract
{
    /**
     * Get an array of all registered Events.
     *
     * @return ScheduledEventContract[]
     */
    public function events(): array;

    /**
     * Pass in the current Date to allow us to check events that are due to run.
     *
     * @throws RuntimeException Thrown when the date has not properly been set.
     * @return Carbon
     */
    public function date(): Carbon;

    /**
     * Set the current Date.
     *
     * @param Carbon $date
     *
     * @return $this
     */
    public function setDate(Carbon $date): self;

    /**
     * Add a new Event into the Scheduler.
     *
     * @param ScheduledEventContract $event
     *
     * @return $this
     */
    public function add(ScheduledEventContract $event): self;

    /**
     * Run the Scheduler.
     *
     * @return void.
     */
    public function run(): void;
}

<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Contracts;

use Carbon\Carbon;

interface ScheduledEventContract
{
    /**
     * Handle this Event
     *
     * @return void
     */
    public function handle(): void;

    /**
     * Is this Event due to run.
     *
     * @param Carbon|null $date
     *
     * @return bool
     */
    public function dueToRun(Carbon $date = null): bool;
}

<?php

declare(strict_types=1);

namespace JustSteveKing\Micro\Scheduler;

use Carbon\Carbon;
use JustSteveKing\Micro\Contracts\ScheduledEventContract;
use JustSteveKing\Micro\Contracts\SchedulerContract;
use RuntimeException;

class Kernel implements SchedulerContract
{
    /**
     * @var Carbon
     */
    protected Carbon $date;

    /**
     * Kernel constructor.
     *
     * @param ScheduledEventContract[] $events Default Events to pass to the Kernel.
     */
    public function __construct(
        protected array $events = [],
    ) {}

    /**
     * @inheritDoc
     */
    public function events(): array
    {
        return $this->events;
    }

    /**
     * @inheritDoc
     */
    public function date(): Carbon
    {
        if (! isset($this->date)) {
            throw new RuntimeException(
                "Cannot access date when it has now been set, use the setDate method."
            );
        }

        return $this->date;
    }

    /**
     * @inheritDoc
     */
    public function setDate(Carbon $date): SchedulerContract
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function add(ScheduledEventContract $event): SchedulerContract
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function run(): void
    {
        foreach ($this->events() as $event) {
            if (! empty($event)) {
                if (! $event->dueToRun(date: $this->date())) {
                    continue;
                }

                $event->handle();
            }
        }
    }
}

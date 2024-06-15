<?php

declare(strict_types=1);

namespace DateAndTime\Type;

use DateTimeImmutable;
use LogicException;
use DateAndTime\Exception\StopwatchAlreadyStartedException;
use DateAndTime\Exception\StopwatchAlreadyStoppedException;
use DateAndTime\Exception\StopwatchNeverStartedException;
use DateAndTime\Exception\StopwatchNeverStoppedException;

class Stopwatch
{
    private string $id;

    private ?DateTimeImmutable $start = null;

    private ?DateTimeImmutable $stop = null;

    public function __construct(string $id, bool $start = true)
    {
        $this->id = $id;

        if ($start) {
            $this->start = new DateTimeImmutable();
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @throws StopwatchAlreadyStartedException
     */
    public function start(): void
    {
        if ($this->start === null) {
            $this->start = new DateTimeImmutable();
            return;
        }

        throw new StopwatchAlreadyStartedException("Stopwatch already started. ID = " . $this->id);
    }

    /**
     * @throws StopwatchAlreadyStoppedException
     */
    public function stop(): void
    {
        if ($this->stop === null) {
            $this->stop = new DateTimeImmutable();
            return;
        }

        throw new StopwatchAlreadyStoppedException("Stopwatch already stopped. ID = " . $this->id);
    }

    /**
     * @throws StopwatchNeverStartedException
     * @throws StopwatchNeverStoppedException
     */
    public function getTimeLapseInSeconds(): float
    {
        $millis = $this->getTimeLapseInMilliseconds();
        return $millis / 1000;
    }

    /**
     * @throws StopwatchNeverStartedException
     * @throws StopwatchNeverStoppedException
     */
    public function getTimeLapseInMilliseconds(): int
    {
        if ($this->start === null) {
            throw new StopwatchNeverStartedException("Stopwatch never started. ID = " . $this->id);
        }

        if ($this->stop === null) {
            throw new StopwatchNeverStoppedException("Stopwatch never stopped. ID = " . $this->id);
        }

        $s = (int)$this->start->format('Uv');
        $e = (int)$this->stop->format('Uv');

        $interval = $e - $s;

        if ($interval < 0) {
            throw new LogicException("Time difference unexpected to be negative: " . $interval);
        }

        return $interval;
    }

    public function isRunning(): bool
    {
        return (($this->start !== null) && ($this->stop === null));
    }
}

<?php

declare(strict_types=1);

namespace DateAndTime\Type;

use DateTimeImmutable;
use RuntimeException;

class Stopwatch
{
    private string $id;

    private DateTimeImmutable $start;

    private ?DateTimeImmutable $stop = null;

    /** @var Stopwatch[] */
    private array $children = [];

    public function __construct(string $id)
    {
        $this->id = $id;

        $this->start = new DateTimeImmutable();
    }

    public function addChild(Stopwatch $child): void
    {
        $childId = $child->getId();
        $thisId = $this->getId();

        $existingChild = $this->children[$childId] ?? null;

        if ($existingChild === null) {
            $this->children[$childId] = $child;
            return;
        }

        throw new RuntimeException("Stopwatch '$childId' is already a child of stopwatch '$thisId'");
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function stop(): void
    {
        if ($this->stop !== null) {
            throw new RuntimeException("Stopwatch already stopped. ID = " . $this->id);
        }

        foreach ($this->children as $c) {
            if ($c->isRunning()) {
                $thisId = $this->getId();
                $childId = $c->getId();

                throw new RuntimeException(
                    "Cannot stop '$thisId' because at least one child is still running: '$childId'"
                );
            }
        }

        $this->stop = new DateTimeImmutable();
    }

    public function getTimeLapseInSeconds(): float
    {
        $millis = $this->getTimeLapseInMilliseconds();
        return round(((float)$millis) / 1000.0, 2);
    }

    public function getTimeLapseInMilliseconds(): int
    {
        if ($this->stop === null) {
            throw new RuntimeException("Stopwatch never stopped. ID = " . $this->id);
        }

        $s = (int)$this->start->format('Uv');
        $e = (int)$this->stop->format('Uv');

        $interval = $e - $s;

        if ($interval < 0) {
            throw new RuntimeException("Time difference unexpected to be negative: " . $interval);
        }

        return $interval;
    }

    public function isRunning(): bool
    {
        return ($this->stop === null);
    }

    /**
     * $format = 0 means "in seconds"
     * $format = 1 means "in milliseconds"
     */
    private function dumpReportRecursively(int $nestLevel, int $format): string
    {
        $result = '';

        $indentSize = 3;

        $indentation = str_pad('', ($nestLevel * $indentSize) - 1, ' ', STR_PAD_LEFT);

        $id = $this->getId();

        if ($format === 0) {
            $timelapse = $this->getTimeLapseInSeconds();
            $result .= ("$indentation-> $id : $timelapse (s)" . PHP_EOL);
        } elseif ($format === 1) {
            $timelapse = $this->getTimeLapseInMilliseconds();
            $result .= ("$indentation-> $id : $timelapse (ms)" . PHP_EOL);
        } else {
            throw new RuntimeException("Unrecognized timelapse units");
        }

        foreach ($this->children as $c) {
            $result .= $c->dumpReportRecursively(++$nestLevel, $format);
        }

        return $result;
    }

    public function dumpReportInSeconds(): string
    {
        return $this->dumpReportRecursively(0, 0);
    }

    public function dumpReportInMilliseconds(): string
    {
        return $this->dumpReportRecursively(0, 1);
    }
}

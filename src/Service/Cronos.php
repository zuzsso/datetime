<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace DateAndTime\Service;

use DateAndTime\Type\Stopwatch;
use RuntimeException;

class Cronos
{
    /** @var Stopwatch[] */
    private static array $stopwatches = [];

    public static function startTraceId(?string $parentId, string $id): void
    {
        if (($parentId === null) && (count(self::$stopwatches) === 0)) {
            // Legit case. No stopwatches added yet, and the first one is the parent
            self::addStopwatch(new Stopwatch($id));
        }

        if (($parentId === null) && (count(self::$stopwatches) > 0)) {
            throw new RuntimeException('The trace has already begun. Please specify a parent for this stopwatch');
        }

        if (($parentId !== null) && (count(self::$stopwatches) > 0)) {
            // Legit case. There are already stopwatches added, and this one is not the parent one
            $parentStopwatch = self::$stopwatches[$parentId] ?? null;
            if ($parentStopwatch === null) {
                throw new RuntimeException("Parent Stopwatch ID not found: $parentId");
            }

            $newStopwatch = new Stopwatch($id);
            self::addStopwatch($newStopwatch);
            $parentStopwatch->addChild($newStopwatch);
        }

        if (($parentId !== null) && (count(self::$stopwatches) === 0)) {
            throw new RuntimeException("The trace hasn't begun yet, so the first stopwatch must not have parent");
        }
    }

    public static function stopTraceId(string $id): void
    {
        $stopwatch = self::$stopwatches[$id] ?? null;

        if ($stopwatch === null) {
            throw new RuntimeException("Stopwatch ID not found: $id");
        }

        $stopwatch->stop();
    }

    public static function getStopwatchById(string $id): Stopwatch
    {
        foreach (self::$stopwatches as $stopwatch) {
            if ($stopwatch->getId() === $id) {
                return $stopwatch;
            }
        }

        throw new RuntimeException("Stopwatch ID not found: $id");
    }

    private static function addStopwatch(Stopwatch $s): void
    {
        $stopwatchId = $s->getId();

        foreach (self::$stopwatches as $stopwatch) {
            if ($stopwatch->getId() === $stopwatchId) {
                throw new RuntimeException("Stopwatch already exists: ID: " . $stopwatchId);
            }
        }

        self::$stopwatches[] = $s;
    }

    private static function getMainStopwatch(): Stopwatch
    {
        if (count(self::$stopwatches) === 0) {
            throw new RuntimeException("Empty stopwatch collection");
        }

        return self::$stopwatches[0];
    }

    public static function dumpReportInSeconds(): string
    {
        return self::getMainStopwatch()->dumpReportInSeconds();
    }

    public static function dumpReportInMilliSeconds(): string
    {
        return self::getMainStopwatch()->dumpReportInMilliseconds();
    }
}

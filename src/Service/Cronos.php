<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace DateAndTime\Service;

use DateAndTime\Type\Stopwatch;
use RuntimeException;
use Throwable;

class Cronos
{
    /** @var Stopwatch[] */
    private static array $stopwatches = [];

    public static function startTraceId(?string $parentId, string $id): void
    {
        if (($parentId === null) && (count(self::$stopwatches) === 0)) {
            // Legit case. No stopwatches added yet, and the first one is the parent
            self::addStopwatch(new Stopwatch($id));
            return;
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
            return;
        }

        if (($parentId !== null) && (count(self::$stopwatches) === 0)) {
            throw new RuntimeException("The trace hasn't begun yet, so the first stopwatch must not have parent");
        }
    }

    public static function stopTraceId(string $id): void
    {
        $stopwatch = self::getStopwatchById($id);

        $stopwatch->stop();
    }

    public static function getStopwatchById(string $id): Stopwatch
    {
        $stopwatch = self::$stopwatches[$id] ?? null;

        if ($stopwatch === null) {
            throw new RuntimeException("Stopwatch ID not found: $id");
        }

        return $stopwatch;
    }

    private static function addStopwatch(Stopwatch $s): void
    {
        $stopwatchId = $s->getId();
        try {
            self::getStopwatchById($stopwatchId);
        } catch (Throwable $t) {
            // Stopwatch does not exist, so it can be added
            self::$stopwatches[$stopwatchId] = $s;
            return;
        }

        throw new RuntimeException("Another stopwatch with the same id already exists: $stopwatchId");
    }

    private static function getMainStopwatch(): Stopwatch
    {
        if (count(self::$stopwatches) === 0) {
            throw new RuntimeException("Empty stopwatch collection");
        }

        $keys = array_keys(self::$stopwatches);

        $firstKey = $keys[0];

        return self::$stopwatches[$firstKey];
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

<?php

declare(strict_types=1);

namespace DateAndTime\Service;

use DateAndTime\Exception\CronosImplementerUnmanagedException;
use DateAndTime\Exception\NoStopwatchesInCollectionException;
use DateAndTime\Exception\StopwatchIdAlreadyExistsException;
use DateAndTime\Exception\StopwatchIdNotFoundException;
use DateAndTime\Type\Stopwatch;
use DateAndTime\UseCase\Cronos;
use Throwable;

class CronosImplementer implements Cronos
{
    /**
     * @var Stopwatch[]
     */
    private static array $stopwatches = [];

    /**
     * @inheritDoc
     */
    public function startTraceId(string $id, bool $start = true): void
    {
        $stopWatch = new Stopwatch($id, $start);

        $this->addStopwatch($stopWatch);
    }

    /**
     * @inheritDoc
     */
    public function stopTraceId(string $id): void
    {
        if (count(self::$stopwatches) === 0) {
            throw new NoStopwatchesInCollectionException("Can't stop stopwatch. Empty stopwatch collection");
        }

        foreach (self::$stopwatches as $stopwatch) {
            $thisId = $stopwatch->getId();
            if ($thisId === $id) {
                $stopwatch->stop();
                return;
            }
        }

        throw new StopwatchIdNotFoundException("Stopwatch ID not found: $id");
    }

    /**
     * @inheritDoc
     */
    public function getStopwatchById(string $id): Stopwatch
    {
        foreach (self::$stopwatches as $stopwatch) {
            if ($stopwatch->getId() === $id) {
                return $stopwatch;
            }
        }

        throw new StopwatchIdNotFoundException("Stopwatch ID not found: $id");
    }

    /**
     * @throws StopwatchIdAlreadyExistsException
     */
    private function addStopwatch(Stopwatch $s): void
    {
        $stopwatchId = $s->getId();

        foreach (self::$stopwatches as $stopwatch) {
            if ($stopwatch->getId() === $stopwatchId) {
                throw new StopwatchIdAlreadyExistsException("Stopwatch already exists: ID: " . $stopwatchId);
            }
        }

        self::$stopwatches[] = $s;
    }

    /**
     * @inheritDoc
     */
    public function dumpReportInSeconds(): string
    {
        if (count(self::$stopwatches) === 0) {
            throw new NoStopwatchesInCollectionException("Empty stopwatch collection. Nothing to report");
        }

        $report = '';

        foreach (self::$stopwatches as $stopwatch) {
            $timelapse = $stopwatch->getTimeLapseInSeconds();
            $id = $stopwatch->getId();

            $report .= ("$id -> $timelapse (s)" . PHP_EOL);
        }

        return $report;
    }

    /**
     * @inheritDoc
     */
    public function dumpReportInMilliSeconds(): string
    {
        if (count(self::$stopwatches) === 0) {
            throw new NoStopwatchesInCollectionException("Empty stopwatch collection. Nothing to report");
        }

        $report = '';

        foreach (self::$stopwatches as $stopwatch) {
            $timelapse = $stopwatch->getTimeLapseInMilliseconds();
            $id = $stopwatch->getId();

            $report .= ("$id -> $timelapse (ms)" . PHP_EOL);
        }

        return $report;
    }

    /**
     * @inheritDoc
     */
    public function stopAllRunningTraces(): void
    {
        try {
            foreach (self::$stopwatches as $sw) {
                if ($sw->isRunning()) {
                    $sw->stop();
                }
            }
        } catch (Throwable $t) {
            throw new CronosImplementerUnmanagedException($t->getMessage(), $t->getCode(), $t);
        }
    }
}

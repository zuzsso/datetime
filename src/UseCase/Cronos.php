<?php

declare(strict_types=1);

namespace DateAndTime\UseCase;

use DateAndTime\Exception\CronosImplementerUnmanagedException;
use DateAndTime\Exception\NoStopwatchesInCollectionException;
use DateAndTime\Exception\StopwatchAlreadyStoppedException;
use DateAndTime\Exception\StopwatchIdAlreadyExistsException;
use DateAndTime\Exception\StopwatchIdNotFoundException;
use DateAndTime\Exception\StopwatchNeverStartedException;
use DateAndTime\Exception\StopwatchNeverStoppedException;
use DateAndTime\Type\Stopwatch;

interface Cronos
{
    /**
     * @throws StopwatchIdAlreadyExistsException
     */
    public function startTraceId(string $id, bool $start = true): void;

    /**
     * @throws StopwatchAlreadyStoppedException
     * @throws NoStopwatchesInCollectionException
     * @throws StopwatchIdNotFoundException
     */
    public function stopTraceId(string $id): void;

    /**
     * @throws StopwatchIdNotFoundException
     */
    public function getStopwatchById(string $id): Stopwatch;

    /**
     * @throws StopwatchNeverStartedException
     * @throws StopwatchNeverStoppedException
     * @throws NoStopwatchesInCollectionException
     */
    public function dumpReportInSeconds(): string;

    /**
     * @throws StopwatchNeverStartedException
     * @throws StopwatchNeverStoppedException
     * @throws NoStopwatchesInCollectionException
     */
    public function dumpReportInMilliSeconds(): string;

    /**
     * @throws CronosImplementerUnmanagedException
     */
    public function stopAllRunningTraces(): void;
}

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
    public static function startTraceId(string $id, bool $start = true): void;

    /**
     * @throws StopwatchAlreadyStoppedException
     * @throws NoStopwatchesInCollectionException
     * @throws StopwatchIdNotFoundException
     */
    public static function stopTraceId(string $id): void;

    /**
     * @throws StopwatchIdNotFoundException
     */
    public static function getStopwatchById(string $id): Stopwatch;

    /**
     * @throws StopwatchNeverStartedException
     * @throws StopwatchNeverStoppedException
     * @throws NoStopwatchesInCollectionException
     */
    public static function dumpReportInSeconds(): string;

    /**
     * @throws StopwatchNeverStartedException
     * @throws StopwatchNeverStoppedException
     * @throws NoStopwatchesInCollectionException
     */
    public static function dumpReportInMilliSeconds(): string;

    /**
     * @throws CronosImplementerUnmanagedException
     */
    public static function stopAllRunningTraces(): void;
}

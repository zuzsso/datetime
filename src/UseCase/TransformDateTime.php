<?php

declare(strict_types=1);

namespace DateAndTime\UseCase;

use DateTimeImmutable;
use DateAndTime\Exception\DatetimeCommonOperationsUnmanagedException;

interface TransformDateTime
{
    public function removeTime(DateTimeImmutable $dateTimeImmutable): DateTimeImmutable;

    /**
     * @throws DatetimeCommonOperationsUnmanagedException
     */
    public function subtractDays(DateTimeImmutable $d, int $days): DateTimeImmutable;

    /**
     * @throws DatetimeCommonOperationsUnmanagedException
     */
    public function subtractSeconds(DateTimeImmutable $d, int $seconds): DateTimeImmutable;

    /**
     * @throws DatetimeCommonOperationsUnmanagedException
     */
    public function addDays(DateTimeImmutable $d, int $days): DateTimeImmutable;

    /**
     * @throws DatetimeCommonOperationsUnmanagedException
     */
    public function addSeconds(DateTimeImmutable $d, int $seconds): DateTimeImmutable;
}

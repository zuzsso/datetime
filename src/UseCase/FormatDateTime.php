<?php

declare(strict_types=1);

namespace DateAndTime\UseCase;

use DateTimeImmutable;
use DateAndTime\Exception\DatetimeCommonOperationsUnmanagedException;

interface FormatDateTime
{
    /**
     * @throws DatetimeCommonOperationsUnmanagedException
     */
    public function fromMySqlDateTimeToImmutable(string $mysqlDateTime): DateTimeImmutable;

    public function fromImmutableToMySqlDateTime(DateTimeImmutable $d): string;
}

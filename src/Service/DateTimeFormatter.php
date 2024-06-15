<?php

declare(strict_types=1);

namespace DateAndTime\Service;

use DateAndTime\Exception\DatetimeCommonOperationsUnmanagedException;
use DateAndTime\UseCase\FormatDateTime;
use DateTimeImmutable;

class DateTimeFormatter implements FormatDateTime
{
    private const MYSQL_DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @inheritDoc
     */
    public function fromMySqlDateTimeToImmutable(string $mysqlDateTime): DateTimeImmutable
    {
        $result = DateTimeImmutable::createFromFormat(self::MYSQL_DATE_TIME_FORMAT, $mysqlDateTime);

        if ($result === false) {
            throw new DatetimeCommonOperationsUnmanagedException(
                "Could not convert literal '$mysqlDateTime' to Date time format " . self::MYSQL_DATE_TIME_FORMAT
            );
        }

        return $result;
    }

    public function fromImmutableToMySqlDateTime(DateTimeImmutable $d): string
    {
        return $d->format(self::MYSQL_DATE_TIME_FORMAT);
    }
}

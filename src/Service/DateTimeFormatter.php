<?php

declare(strict_types=1);

namespace DateAndTime\Service;

use DateAndTime\UseCase\FormatDateTime;
use DateAndTime\UseCase\TransformDateTime;
use DateTimeImmutable;
use RuntimeException;

class DateTimeFormatter implements FormatDateTime
{
    private const MYSQL_DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    private const MYSQL_DATE_FORMAT = 'Y-m-d';
    private const PROJECT_ID_FORMAT = 'YmdHis';
    private const ISO_8601 = 'Y-m-d\TH:i:sO';

    private TransformDateTime $transformDateTime;

    public function __construct(TransformDateTime $transformDateTime)
    {
        $this->transformDateTime = $transformDateTime;
    }

    /**
     * @inheritDoc
     */
    public function fromMySqlDateTimeToImmutable(string $mysqlDateTime): DateTimeImmutable
    {
        $result = DateTimeImmutable::createFromFormat(self::MYSQL_DATE_TIME_FORMAT, $mysqlDateTime);

        if ($result === false) {
            throw new RuntimeException(
                "Could not convert literal '$mysqlDateTime' to Date time format " . self::MYSQL_DATE_TIME_FORMAT
            );
        }

        return $result;
    }

    public function fromImmutableToMySqlDateTime(DateTimeImmutable $d): string
    {
        return $d->format(self::MYSQL_DATE_TIME_FORMAT);
    }

    /**
     * @inheritDoc
     */
    public function fromMySqlDateToImmutable(string $mysqlDate): DateTimeImmutable
    {
        $result = DateTimeImmutable::createFromFormat(self::MYSQL_DATE_FORMAT, $mysqlDate);

        if ($result === false) {
            throw new RuntimeException(
                "Could not convert literal '$mysqlDate' to Date time format " . self::MYSQL_DATE_FORMAT
            );
        }

        return $this->transformDateTime->removeTime($result);
    }

    public function fromImmutableToMySqlDate(DateTimeImmutable $d): string
    {
        return $d->format(self::MYSQL_DATE_FORMAT);
    }

    public function fromImmutableToProjectId(DateTimeImmutable $d): int
    {
        return (int)$d->format(self::PROJECT_ID_FORMAT);
    }

    /**
     * @inheritDoc
     */
    public function fromStringToIso8601(string $dateAsString): DateTimeImmutable
    {
        $result = DateTimeImmutable::createFromFormat(self::ISO_8601, $dateAsString);

        if ($result === false) {
            throw new RuntimeException(
                "Could not convert literal '$dateAsString' to Date time format " . self::ISO_8601
            );
        }

        return $result;
    }

    public function fromImmutableToIso8601(DateTimeImmutable $d): string
    {
        return $d->format(self::ISO_8601);
    }
}

<?php

declare(strict_types=1);

namespace DateAndTime\Service;

use DateAndTime\UseCase\FormatDateTime;
use DateTimeImmutable;
use DateAndTime\UseCase\CompareDateTime;

class DateTimeComparator implements CompareDateTime
{
    private FormatDateTime $formatDateTime;

    public function __construct(FormatDateTime $formatDateTime)
    {
        $this->formatDateTime = $formatDateTime;
    }

    /**
     * @inheritDoc
     */
    public function getDifferenceInSeconds(DateTimeImmutable $d1, DateTimeImmutable $d2, bool $absolute = false): int
    {
        $ts1 = $d1->getTimestamp();
        $ts2 = $d2->getTimestamp();

        $diff = $ts2 - $ts1;

        if ($absolute) {
            return (int)(abs($diff));
        }

        return $diff;
    }

    public function equalsMySqlDateTimeFormat(DateTimeImmutable $d1, DateTimeImmutable $d2): bool
    {
        $f1 = $this->formatDateTime->fromImmutableToMySqlDateTime($d1);
        $f2 = $this->formatDateTime->fromImmutableToMySqlDateTime($d2);

        return $f1 === $f2;
    }
}

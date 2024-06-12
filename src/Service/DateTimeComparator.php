<?php

declare(strict_types=1);

namespace DateAndTime\Service;

use DateTimeImmutable;
use DateAndTime\UseCase\CompareDateTime;

class DateTimeComparator implements CompareDateTime
{
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
}

<?php

declare(strict_types=1);

namespace DateAndTime\Service;

use DateInterval;
use DateTimeImmutable;
use DateAndTime\Exception\DatetimeCommonOperationsUnmanagedException;
use DateAndTime\UseCase\TransformDateTime;

class DateTimeTransformer implements TransformDateTime
{
    public function removeTime(DateTimeImmutable $dateTimeImmutable): DateTimeImmutable
    {
        return $dateTimeImmutable->modify('midnight');
    }

    /**
     * @inheritDoc
     */
    public function subtractDays(DateTimeImmutable $d, int $days): DateTimeImmutable
    {
        $this->checkStrictlyPositive($days);

        return $d->sub(new DateInterval("P${days}D"));
    }

    /**
     * @inheritDoc
     */
    public function subtractSeconds(DateTimeImmutable $d, int $seconds): DateTimeImmutable
    {
        $this->checkStrictlyPositive($seconds);

        return $d->sub(new DateInterval("PT${seconds}S"));
    }

    /**
     * @inheritDoc
     */
    public function addSeconds(DateTimeImmutable $d, int $seconds): DateTimeImmutable
    {
        $this->checkStrictlyPositive($seconds);

        return $d->add(new DateInterval("PT${seconds}S"));
    }

    /**
     * @inheritDoc
     */
    public function addDays(DateTimeImmutable $d, int $days): DateTimeImmutable
    {
        $this->checkStrictlyPositive($days);

        return $d->add(new DateInterval("P${days}D"));
    }

    /**
     * @throws DatetimeCommonOperationsUnmanagedException
     */
    private function checkStrictlyPositive(int $value): void
    {
        if ($value <= 0) {
            throw new DatetimeCommonOperationsUnmanagedException(
                "This function requires a positive number of days and greater than 0 to substract. Provided: $value"
            );
        }
    }
}

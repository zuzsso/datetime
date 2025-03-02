<?php

declare(strict_types=1);

namespace DateAndTime\UseCase;

use DateTimeImmutable;

interface TransformDateTime
{
    public function removeTime(DateTimeImmutable $dateTimeImmutable): DateTimeImmutable;

    public function subtractDays(DateTimeImmutable $d, int $days): DateTimeImmutable;

    public function subtractSeconds(DateTimeImmutable $d, int $seconds): DateTimeImmutable;

    public function addDays(DateTimeImmutable $d, int $days): DateTimeImmutable;

    public function addSeconds(DateTimeImmutable $d, int $seconds): DateTimeImmutable;
}

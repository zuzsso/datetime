<?php

declare(strict_types=1);

namespace DateAndTime\Service;

use DateTimeImmutable;
use DateAndTime\UseCase\ProvideDateTime;

class DateTimeProvider implements ProvideDateTime
{
    public function getSystemDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }
}

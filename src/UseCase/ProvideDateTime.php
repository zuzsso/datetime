<?php

namespace DateAndTime\UseCase;

use DateAndTime\Exception\DatetimeCommonOperationsUnmanagedException;
use DateAndTime\Exception\DateTimeProviderUnmanagedException;
use DateTimeImmutable;

interface ProvideDateTime
{
    public function getSystemDateTime(): DateTimeImmutable;

    /**
     * @throws DateTimeProviderUnmanagedException
     * @throws DatetimeCommonOperationsUnmanagedException
     */
    public function getExternalUtcDateTime(): DateTimeImmutable;
}

<?php

namespace DateAndTime\UseCase;

use DateTimeImmutable;

interface ProvideDateTime
{
    public function getSystemDateTime(): DateTimeImmutable;

    public function getExternalUtcDateTime(): DateTimeImmutable;
}

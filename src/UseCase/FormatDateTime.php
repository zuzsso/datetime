<?php

declare(strict_types=1);

namespace DateAndTime\UseCase;

use DateTimeImmutable;

interface FormatDateTime
{
    public function fromMySqlDateTimeToImmutable(string $mysqlDateTime): DateTimeImmutable;

    public function fromImmutableToMySqlDateTime(DateTimeImmutable $d): string;

    public function fromMySqlDateToImmutable(string $mysqlDate): DateTimeImmutable;

    public function fromImmutableToMySqlDate(DateTimeImmutable $d): string;

    public function fromImmutableToProjectId(DateTimeImmutable $d): int;

    public function fromStringToIso8601(string $dateAsString): DateTimeImmutable;

    public function fromImmutableToIso8601(DateTimeImmutable $d): string;
}

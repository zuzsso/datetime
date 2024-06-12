<?php

declare(strict_types=1);

namespace DateAndTime\UseCase;

use DateTimeImmutable;
use Language\Type\Language\AbstractLanguage;

interface SerializeDateTime
{
    public function serializeImmutableForLanguage(DateTimeImmutable $dateTime, AbstractLanguage $lan): array;
}

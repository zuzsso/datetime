<?php

declare(strict_types=1);

namespace DateAndTime\Tests\DateAndTime\Service;

use DateAndTime\Service\DateTimeFormatter;
use DateAndTime\Service\DateTimeProvider;
use DateAndTime\Service\DateTimeTransformer;
use PHPUnit\Framework\TestCase;

class DateTimeProviderTest extends TestCase
{
    private DateTimeProvider $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new DateTimeProvider(new DateTimeFormatter(new DateTimeTransformer()));
    }

    public function testConnectivity(): void
    {
        $this->sut->getExternalUtcDateTime();

        $this->expectNotToPerformAssertions();
    }
}

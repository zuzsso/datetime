<?php

declare(strict_types=1);

namespace DateAndTime\Tests\DateAndTime\Service;

use DateAndTime\Tests\CustomTestCase;
use DateTimeImmutable;
use DateAndTime\Service\DateTimeSerializer;
use Language\Type\Language\English;

class DateTimeSerializerTest extends CustomTestCase
{
    private DateTimeSerializer $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new DateTimeSerializer();
    }

    public function testCorrectlySerializes(): void
    {
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-07-08 18:35:45');
        $actual = $this->sut->serializeImmutableForLanguage($date, new English());

        $expected = [
            'components' => [
                'day' => 8,
                'month' => 7,
                'year' => 2023,
                'hour' => 18,
                'minute' => 35,
                'second' => 45
            ],
            'formatted' => [
                'longDate' => 'July 8th, 2023',
                'dateTimeMonospace' => '08 Jul 2023 18:35:45',
                'time24H' => '18:35:45',
                'dateMonospace' => '08 Jul 2023'
            ]
        ];

        self::assertEquals($expected, $actual);
    }
}

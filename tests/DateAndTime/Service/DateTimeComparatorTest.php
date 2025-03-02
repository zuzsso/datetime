<?php

declare(strict_types=1);

namespace DateAndTime\Tests\DateAndTime\Service;

use DateAndTime\Tests\CustomTestCase;
use DateAndTime\UseCase\FormatDateTime;
use DateTimeImmutable;
use DateTimeZone;
use DateAndTime\Service\DateTimeComparator;

class DateTimeComparatorTest extends CustomTestCase
{
    private DateTimeComparator $sut;

    public function setUp(): void
    {
        parent::setUp();

        $formatDateTime = $this->createMock(FormatDateTime::class);

        $this->sut = new DateTimeComparator($formatDateTime);
    }

    public function returnsCorrectResultsDataProvider(): array
    {
        $d1 = new DateTimeImmutable();

        $d2 = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-04-02 19:23:00');
        $d3 = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-04-02 19:23:15');

        $d4 = DateTimeImmutable::createFromFormat(
            'Y-m-d H:i:s',
            '2024-04-02 19:23:00',
            new DateTimeZone('GMT')
        );

        $d5 = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2024-04-02 19:23:00', new DateTimeZone('PST'));

        return [
            // Same date and time and timezone, duh
            [$d1, $d1, true, 0],
            [$d1, $d1, false, 0],

            // Same time zone, 15 secs diff
            [$d2, $d3, true, 15],
            [$d2, $d3, false, 15],

            // Same time zone, 15 secs diff, reversed operands
            [$d3, $d2, true, 15],
            [$d3, $d2, false, -15],

            // Same date and time, but completely different timezones
            [$d4, $d5, true, 28800],
            [$d4, $d5, false, 28800],

            [$d5, $d4, true, 28800],
            [$d5, $d4, false, -28800]
        ];
    }

    /**
     * @dataProvider returnsCorrectResultsDataProvider
     */
    public function testReturnsCorrectResults(
        DateTimeImmutable $d1,
        DateTimeImmutable $d2,
        bool $absolute,
        int $expected
    ): void {
        $actual = $this->sut->getDifferenceInSeconds($d1, $d2, $absolute);

        self::assertEquals($expected, $actual);
    }
}

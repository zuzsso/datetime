<?php

declare(strict_types=1);

namespace DateAndTime;

use DateAndTime\Service\CronosImplementer;
use DateAndTime\UseCase\Cronos;
use DiManifest\AbstractDependencyInjection;
use DateAndTime\Service\DateTimeComparator;
use DateAndTime\Service\DateTimeFormatter;
use DateAndTime\Service\DateTimeProvider;
use DateAndTime\Service\DateTimeSerializer;
use DateAndTime\Service\DateTimeTransformer;
use DateAndTime\UseCase\CompareDateTime;
use DateAndTime\UseCase\FormatDateTime;
use DateAndTime\UseCase\ProvideDateTime;
use DateAndTime\UseCase\SerializeDateTime;
use DateAndTime\UseCase\TransformDateTime;

use function DI\autowire;

class DependencyInjectionManifest extends AbstractDependencyInjection
{
    public static function getDependencies(): array
    {
        return array_merge(
            [
                Cronos::class => autowire(CronosImplementer::class),
                CompareDateTime::class => autowire(DateTimeComparator::class),
                FormatDateTime::class => autowire(DateTimeFormatter::class),
                ProvideDateTime::class => autowire(DateTimeProvider::class),
                TransformDateTime::class => autowire(DateTimeTransformer::class),
                SerializeDateTime::class => autowire(DateTimeSerializer::class),
            ]
        );
    }
}

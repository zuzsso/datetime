<?php

declare(strict_types=1);

namespace DateAndTime\Service;

use DateAndTime\Exception\DateTimeProviderUnmanagedException;
use DateAndTime\UseCase\FormatDateTime;
use DateTimeImmutable;
use DateAndTime\UseCase\ProvideDateTime;
use DOMDocument;
use DOMNodeList;
use DOMXPath;

class DateTimeProvider implements ProvideDateTime
{
    private FormatDateTime $formatDateTime;

    public function __construct(FormatDateTime $formatDateTime)
    {
        $this->formatDateTime = $formatDateTime;
    }

    public function getSystemDateTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }

    /**
     * @inheritDoc
     */
    public function getExternalUtcDateTime(): DateTimeImmutable
    {
        $externalServer = "https://www.utctime.net/";

        // Won't throw exception if the page is not well-formed
        // @see https://stackoverflow.com/questions/29987361/scraping-malformed-html-with-php-domdocument
        libxml_use_internal_errors(true);

        $html = new DOMDocument();
        $html->loadHTMLFile($externalServer);
        $xpath = new DOMXPath($html);
        $nodeList = $xpath->query("//table//tr");

        if (!$nodeList instanceof DOMNodeList) {
            throw new DateTimeProviderUnmanagedException("Could not get element table with formats");
        }

        $iso8601Format = null;

        foreach ($nodeList as $row) {
            /** @var DOMNodeList $cells */
            $cells = $row->getElementsByTagName('td');

            if ($cells->count() === 2) {
                $first = $cells->item(0);
                $second = $cells->item(1);

                $bothAvailable = !(($first === null) || ($second === null));

                if ($bothAvailable && $first->nodeValue === 'ISO-8601') {
                    $iso8601Format = $second->nodeValue;
                    break;
                }
            }
        }

        if ($iso8601Format === null) {
            throw new DateTimeProviderUnmanagedException("Could not get utc date from format list");
        }

        return $this->formatDateTime->fromStringToIso8601($iso8601Format);
    }
}

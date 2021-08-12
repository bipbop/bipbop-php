<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\NameByCPFCNPJ;
use BIPBOP\Client\WebService;
use PHPUnit\Framework\TestCase;

/**
 * Class NameByCPFCNPJTest.
 *
 * @covers \BIPBOP\Client\NameByCPFCNPJ
 */
class NameByCPFCNPJTest extends TestCase
{
    public function testEvaluate(): void
    {
        $webService = \Mockery::mock(WebService::class);
        $document = new \DOMDocument();
        $document->loadXML(file_get_contents(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'mock',
            'empty.xml'
        ])));
        $webService->shouldReceive("post")
            ->andReturn($document)
            ->once();
        $this->assertEquals(
            "LUCAS FERNANDO AMORIM",
            NameByCPFCNPJ::evaluate("37554311816", "08/06/1990", $webService)
        );
    }
}

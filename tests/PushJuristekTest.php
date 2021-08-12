<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\PushJuristek;
use BIPBOP\Client\WebService;
use PHPUnit\Framework\TestCase;

/**
 * Class PushJuristekTest.
 *
 * @covers \BIPBOP\Client\PushJuristek
 * @covers \BIPBOP\Client\ProviderPush
 */
class PushJuristekTest extends TestCase
{
    /**
     * @var PushJuristek
     */
    protected $providerPush;
    /**
     * @var WebService|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $webService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->webService = \Mockery::mock(WebService::class);
        $this->providerPush = new PushJuristek($this->webService);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->providerPush);
    }

    public function testCreate(): void
    {
        $pushCreation = new \DOMDocument();
        $pushCreation->loadXML(file_get_contents(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'mock',
            'push-create.xml'
        ])));

        $this->webService->shouldReceive("post")
            ->with('INSERT INTO \'PUSHJURISTEK\'.\'JOB\'', [
                'THIS IS A TEST' => 'ok?',
                'pushLabel' => 'EXAMPLE',
                'pushQuery' => 'SELECT FROM \'JURISTEK\'.\'PUSH\'',
                'data' => "SELECT FROM 'INFO'.'INFO' WHERE 'THIS IS A TEST' = 'ok?'",
                'juristekCallback' => 'http://g1.com.br'
            ])
            ->once()
            ->andReturn($pushCreation);

        $this->assertEquals("12345", $this->providerPush->create(
            "EXAMPLE",
            "http://g1.com.br",
            "SELECT FROM 'INFO'.'INFO'",
            [
                "THIS IS A TEST" => "ok?"
            ]));
    }
}

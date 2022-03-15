<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\ProviderPush;
use BIPBOP\Client\WebService;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

/**
 * Class ProviderPushTest.
 *
 * @covers \BIPBOP\Client\ProviderPush
 */
class ProviderPushTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var ProviderPush
     */
    protected $providerPush;

    /**
     * @var WebService|Mock
     */
    protected $webService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->webService = Mockery::mock(WebService::class);
        $this->providerPush = $this->getMockBuilder(ProviderPush::class)
            ->setConstructorArgs([$this->webService])
            ->getMockForAbstractClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->providerPush);
        unset($this->webService);
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
            ->with('INSERT INTO \'PUSH\'.\'JOB\'', [
                'THIS IS A TEST' => 'ok?',
                'pushLabel' => 'EXAMPLE',
                'pushQuery' => 'SELECT FROM \'INFO\'.\'INFO\'',
                'pushCallback' => 'http://g1.com.br',
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

    public function testDelete(): void
    {
        $this->webService->shouldReceive("post")
            ->with('DELETE FROM \'PUSH\'.\'JOB\'', ['id' => 'hi'])
            ->once()
            ->andReturn(new \DOMDocument());
        $this->providerPush->delete("hi");
    }

    public function testOpen(): void
    {
        $this->webService->shouldReceive("post")
            ->with('SELECT FROM \'PUSH\'.\'DOCUMENT\'', ['id' => 'hi'])
            ->once()
            ->andReturn(new \DOMDocument());
        $this->providerPush->open("hi");
    }

    public function testChangeInterval(): void
    {
        $this->webService->shouldReceive("post")
            ->with('UPDATE \'PUSH\'.\'PUSHINTERVAL\'', ['id' => 'hi', 'pushInterval' => 300])
            ->once()
            ->andReturn(new \DOMDocument());
        $this->providerPush->changeInterval("hi", 300);
    }

    public function testChangeMaxVersion(): void
    {
        $this->webService->shouldReceive("post")
            ->with('UPDATE \'PUSH\'.\'PUSHMAXVERSION\'', ['id' => 'hi', 'pushMaxVersion' => 300])
            ->once()
            ->andReturn(new \DOMDocument());
        $this->providerPush->changeMaxVersion("hi", 300);
    }
}

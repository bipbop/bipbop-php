<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Receiver;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use React\Stream\ReadableResourceStream;
use Psr\Http\Message\StreamInterface;

/**
 * Class ReceiverTest.
 *
 * @covers \BIPBOP\Client\Receiver
 */
class ReceiverTest extends TestCase
{
    /**
     * @var RequestInterface|Mock
     */
    protected $request;

    protected Receiver $receiver;
    private string $id;
    private string $label;
    private int $version;
    private string $file;

    /**
     * @var Mockery\LegacyMockInterface|Mockery\MockInterface|StreamInterface
     */
    private $body;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->id = uniqid();
        $this->label = uniqid();
        $this->version = mt_rand();

        $this->file = implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'mock',
            'empty.xml'
        ]);

        $this->body = Mockery::mock(StreamInterface::class);

        $file = file_get_contents($this->file);

        $this->body->shouldReceive("read")->andReturn($file);
        $this->body->shouldReceive("getSize")->andReturn(strlen($file));

        $this->request = Mockery::mock(RequestInterface::class);
        $this->request
            ->shouldReceive("getServerParams")
            ->andReturn([
                'HTTP_X_BIPBOP_VERSION' => (string)$this->version,
                'HTTP_X_BIPBOP_DOCUMENT_ID' => $this->id,
                'HTTP_X_BIPBOP_DOCUMENT_LABEL' => $this->label
            ]);

        $this->request
            ->shouldReceive("getBody")
            ->andReturn($this->body);

        $this->receiver = new Receiver($this->request);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->receiver);
        unset($this->request);
    }

    public function testDocument(): void
    {
        $this->assertEquals($this->id, $this->receiver->getId());
        $this->assertEquals($this->label, $this->receiver->getLabel());
        $this->assertEquals($this->version, $this->receiver->getVersion());
        $this->assertXmlStringEqualsXmlFile($this->file, $this->receiver->document()->saveXML());
    }
}

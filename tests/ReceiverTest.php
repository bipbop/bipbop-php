<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Receiver;
use BIPBOP\Client\Tags;
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
    protected $request;
    protected $receiver;
    protected $id;
    protected $label;
    protected $version;
    protected $file;
    protected $body;
    /**
     * @var string[]
     */
    protected $tags;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var bool
     */
    protected $exception;

    /**
     * @var string
     */
    protected $memoryId;

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
        $this->tags = ['select.juristek.cnj.processo','select.juristek.cnj','select.juristek','select'];

        $file = file_get_contents($this->file);

        $this->body->shouldReceive("read")->andReturn($file);
        $this->body->shouldReceive("getSize")->andReturn(strlen($file));

        $this->company = 'automatic_0@jusbrasil';
        $this->apiKey= 'xxxxxxxxxxxxxxxxxxxxxx';
        $this->exception = false;
        $this->memoryId = '0000000000000000000000';

        $this->request = Mockery::mock(RequestInterface::class);
        $this->request
            ->shouldReceive("getServerParams")
            ->andReturn([
                'HTTP_X_BIPBOP_COMPANY' => $this->company,
                'HTTP_X_BIPBOP_APIKEY' => $this->apiKey,
                'HTTP_X_BIPBOP_TAGS' => Tags::encode($this->tags),
                'HTTP_X_BIPBOP_MEMORY_ID' => $this->memoryId,
                'HTTP_X_BIPBOP_EXCEPTION' => $this->exception ? 'true' : false,
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
        $this->assertEquals($this->apiKey, $this->receiver->getApiKey());
        $this->assertEquals($this->company, $this->receiver->getCompany());
        $this->assertEquals($this->exception, $this->receiver->isException());
        $this->assertEquals($this->memoryId, $this->receiver->getMemoryId());
        $this->assertEquals($this->tags, $this->receiver->getTags());

        $this->assertXmlStringEqualsXmlFile($this->file, $this->receiver->document()->saveXML());
    }
}

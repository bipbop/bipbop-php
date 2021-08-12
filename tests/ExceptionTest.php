<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class ExceptionTest.
 *
 * @covers \BIPBOP\Client\Exception
 */
class ExceptionTest extends TestCase
{
    protected $exception;
    private $code;
    private $id;
    private $source;
    private $message;
    private $pushable;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->id = uniqid();
        $this->code = 0;
        $this->source = Exception::class;
        $this->message = "random";
        $this->pushable = false;
        /** @todo Correctly instantiate tested object to use it. */
        $this->exception = new Exception();
        $this->exception->configure(
            $this->code,
            $this->source,
            $this->id,
            $this->message,
            $this->pushable,
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->exception);
    }

    public function testProviderCode(): void
    {
        $this->assertEquals($this->code, $this->exception->providerCode());
    }

    public function testProviderSource(): void
    {
        $this->assertEquals($this->source, $this->exception->providerSource());
    }

    public function testProviderId(): void
    {
        $this->assertEquals($this->id, $this->exception->providerId());
    }

    public function testProviderMessage(): void
    {
        $this->assertEquals($this->message, $this->exception->providerMessage());
    }

    public function testProviderPushable(): void
    {
        $this->assertEquals($this->pushable, $this->exception->providerPushable());
    }
}

<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Receiver;
use Mockery;
use Mockery\Mock;
use Psr\Http\Message\ServerRequestInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ReceiverTest.
 *
 * @covers \BIPBOP\Client\Receiver
 */
class ReceiverTest extends TestCase
{
    /**
     * @var Receiver
     */
    protected $receiver;

    /**
     * @var ServerRequestInterface|Mock
     */
    protected $request;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = Mockery::mock(ServerRequestInterface::class);
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
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}

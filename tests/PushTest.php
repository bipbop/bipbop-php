<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Push;
use PHPUnit\Framework\TestCase;

/**
 * Class PushTest.
 *
 * @covers \BIPBOP\Client\Push
 */
class PushTest extends TestCase
{
    /**
     * @var Push
     */
    protected $push;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /** @todo Correctly instantiate tested object to use it. */
        $this->push = new Push();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->push);
    }
}

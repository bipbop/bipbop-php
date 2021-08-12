<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Exception;
use BIPBOP\Client\WebService;
use PHPUnit\Framework\TestCase;

/**
 * Class WebServiceTest.
 *
 * @covers \BIPBOP\Client\WebService
 */
class WebServiceTest extends TestCase
{
    /**
     * @var WebService
     */
    protected $webService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->webService = new WebService();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->webService);
        unset($this->apiKey);
    }

    public function testPost(): void
    {
        $this->assertInstanceOf(
            \DOMDocument::class,
            $this->webService->post("SELECT FROM 'INFO'.'INFO'")
        );
    }

    public function testAssert(): void
    {
        $this->expectException(Exception::class);
        $this->webService->post("SELECT FROM 'INFO'");
    }
}

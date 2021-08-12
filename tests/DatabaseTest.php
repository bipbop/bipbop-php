<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Database;
use BIPBOP\Client\Exception;
use BIPBOP\Client\Table;
use BIPBOP\Client\WebService;
use DOMDocument;
use DOMElement;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

/**
 * Class DatabaseTest.
 *
 * @covers \BIPBOP\Client\Database
 * @covers \BIPBOP\Client\Table
 */
class DatabaseTest extends TestCase
{
    protected Database $database;

    /**
     * @var WebService|Mock
     */
    protected $ws;

    protected DOMElement $domNode;
    protected DOMDocument $dom;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->dom = new \DOMDocument();
        $this->dom->loadXML(file_get_contents(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'mock',
            'info.xml'
        ])));

        $this->domNode = $this->dom->getElementsByTagName("database")->item(0);

        $this->ws = Mockery::mock(WebService::class);
        $this->database = new Database($this->ws, $this->domNode, $this->dom);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->database);
        unset($this->ws);
        unset($this->domNode);
        unset($this->dom);
    }

    public function testName(): void
    {
        $this->assertEquals(
            $this->domNode->getAttribute("name"),
            $this->database->name());
    }

    public function testListTables(): void
    {
        $this->assertEquals(array_map(
            fn(DOMElement $table) => $table->getAttribute("name"),
            iterator_to_array($this->domNode->getElementsByTagName("table"))
        ), array_map(
            fn($table) => $table['name'],
            iterator_to_array($this->database->listTables())
        ));
    }

    public function testGetTable(): void
    {
        $name = $this->domNode->getElementsByTagName("table")->item(0)->getAttribute("name");
        $this->assertInstanceOf(Table::class, $this->database->getTable($name));
        $this->expectException(Exception::class);
        $this->database->getTable("invalidName");
    }

    public function testGet(): void
    {
        $this->assertEquals(
            $this->domNode->getAttribute("name"),
            $this->database->name()
        );
    }
}

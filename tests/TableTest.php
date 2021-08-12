<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Database;
use BIPBOP\Client\Field;
use BIPBOP\Client\Table;
use BIPBOP\Client\WebService;
use DOMDocument;
use DOMElement;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * Class TableTest.
 *
 * @covers \BIPBOP\Client\Table
 * @covers \BIPBOP\Client\ProviderPush
 * @covers \BIPBOP\Client\Field
 */
class TableTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    private Table $table;
    private Database $database;
    private WebService $ws;
    private DOMDocument $dom;
    private DOMElement $domNode;


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

        $this->domNode = $this->dom->getElementsByTagName("table")->item(0);

        $this->ws = Mockery::mock(WebService::class);
        $this->database = Mockery::mock(Database::class);
        $this->ws->shouldReceive("post")
            ->with("SELECT FROM 'INFO'.'INFO'", [])
            ->andReturn($this->dom);

        $this->table = new Table($this->ws, $this->database, $this->domNode, $this->dom);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->table);
        unset($this->ws);
        unset($this->database);
        unset($this->domNode);
        unset($this->dom);
    }

    public function testName(): void
    {
        $this->assertEquals(
            $this->domNode->getAttribute("name"),
            $this->table->name()
        );
    }

    public function testGetFields(): void
    {
        $this->assertEquals(array_map(
            fn(Field $field) => $field->name(),
            iterator_to_array($this->table->getFields()),
        ), array_map(
            fn(DOMElement $e) => $e->getAttribute("name"),
            iterator_to_array($this->domNode->getElementsByTagName("field"))
        ));
    }

    public function testDatabase(): void
    {
        $this->assertSame($this->database, $this->table->database());
    }

    public function testGeneratePush(): void
    {
        $pushCreation = new DOMDocument();
        $pushCreation->loadXML(file_get_contents(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'mock',
            'push-create.xml'
        ])));

        $this->database
            ->shouldReceive("name")
            ->andReturn('DBPUSH');

        $this->ws->shouldReceive("post")
            ->with('INSERT INTO \'PUSH\'.\'JOB\'', [
                'hello' => 'parameter',
                'pushLabel' => 'my-label',
                'pushQuery' => 'SELECT FROM \'DBPUSH\'.\'CONSULTA\'',
                'pushCallback' => 'https://g1.com.br/'
            ])->andReturn($pushCreation);

        $this->assertEquals(
            $pushCreation->getElementsByTagName("id")->item(0)->nodeValue,
            $this->table->generatePush(
            [
                "hello" => "parameter"
            ],
            "my-label",
            "https://g1.com.br/",
        ));
    }

    public function testGet(): void
    {
        $this->assertEquals(
            $this->domNode->getAttribute("name"),
            $this->table->get("name")
        );
    }
}

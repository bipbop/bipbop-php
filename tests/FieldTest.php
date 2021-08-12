<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Database;
use BIPBOP\Client\Field;
use BIPBOP\Client\Table;
use DOMDocument;
use DOMElement;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

/**
 * Class FieldTest.
 *
 * @covers \BIPBOP\Client\Field
 */
class FieldTest extends TestCase
{

    /**
     * @var Table|Mock
     */
    protected $table;

    /**
     * @var Database|Mock
     */
    protected $database;

    protected DOMElement $domNode;
    protected DOMDocument $dom;
    protected Field $field;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->table = Mockery::mock(Table::class);
        $this->database = Mockery::mock(Database::class);
        $this->dom = new DOMDocument();
        $this->dom->loadXML(file_get_contents(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'mock',
            'info.xml'
        ])));
        $this->domNode = $this->dom->getElementsByTagName("field")->item(0);
        $this->field = new Field($this->table, $this->database, $this->domNode, $this->dom);
    }

    public function testTable(): void
    {
        $this->assertSame($this->table, $this->field->table());
    }

    public function testDatabase(): void
    {
        $this->assertSame($this->database, $this->field->database());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->field);
        unset($this->table);
        unset($this->database);
        unset($this->domNode);
        unset($this->dom);
    }

    public function testGet(): void
    {
        $this->assertEquals(
            $this->domNode->getAttribute("name"),
            $this->field->get("name")
        );
    }

    public function testOptions(): void
    {
        $this->assertEquals([[
            '194',
            'PROCURADORIA DA REPUBLICA - ALAGOAS/UNIÃO DOS PALMARES',
        ], [
            '194',
            'PROCURADORIA DA REPUBLICA - ALAGOAS/UNIÃO DOS PALMARES',
        ]], $this->field->options());
    }

    public function testName(): void
    {
        $this->assertEquals(
            $this->domNode->getAttribute("name"),
            $this->field->name()
        );
    }

    public function testGroupOptions(): void
    {
        $this->assertEquals([[
            'AC', [[
                '141',
                'PROCURADORIA DA REPUBLICA - ACRE',
            ], [
                '12812',
                'PROCURADORIA DA REPUBLICA NO MUNICIPIO DE CRUZEIRO DO SUL-AC',
            ]]], [
            'AL', [[
                '194',
                'PROCURADORIA DA REPUBLICA - ALAGOAS/UNIÃO DOS PALMARES',
            ], [
                '230',
                'PROCURADORIA DA REPUBLICA NO MUNICIPIO DE ARAPIRACA/S IPANEM',
            ]]]], $this->field->groupOptions());
    }
}

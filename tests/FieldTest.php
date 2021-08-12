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
     * @var Field
     */
    protected $field;

    /**
     * @var Table|Mock
     */
    protected $table;

    /**
     * @var Database|Mock
     */
    protected $database;

    /**
     * @var DOMElement|Mock
     */
    protected $domNode;

    /**
     * @var DOMDocument|Mock
     */
    protected $dom;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->table = Mockery::mock(Table::class);
        $this->database = Mockery::mock(Database::class);
        $this->domNode = Mockery::mock(DOMElement::class);
        $this->dom = Mockery::mock(DOMDocument::class);
        $this->field = new Field($this->table, $this->database, $this->domNode, $this->dom);
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
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testOptions(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testName(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGroupOptions(): void
    {
        /** @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}

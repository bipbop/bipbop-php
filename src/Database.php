<?php

declare(strict_types=1);

namespace BIPBOP\Client;

class Database
{
    public const KEY_TABLE_NAME = 'name';
    public const KEY_TABLE_DESCRIPTION = 'description';
    public const KEY_TABLE_URL = 'url';

    protected WebService $ws;
    protected \DOMElement $domNode;
    protected \DOMDocument $dom;
    protected \DOMXPath $xpath;

    public function __construct(
        WebService $ws,
        \DOMNode $domNode,
        \DOMDocument $dom
    ) {
        $this->ws = $ws;
        $this->domNode = $domNode;
        $this->dom = $dom;
        $this->xpath = new \DOMXPath($dom);
    }

    public function name(): string
    {
        return $this->domNode->getAttribute('name');
    }

    public function listTables(): \Generator
    {
        $tables = $this->xpath->query('./table', $this->domNode);
        foreach ($tables as $table) {
            yield $this->parseTable($table);
        }
    }

    /**
     * @throws Exception
     */
    public function getTable(string $name): Table
    {
        $findTable = $this->xpath->query(sprintf(
            "./table[@name='%s']",
            preg_replace(
                '/[^a-z0-9-_]/i',
                '',
                $name
            )
        ), $this->domNode);
        if (! $findTable->length) {
            throw new Exception("can't find table '${name}'");
        }

        $tableNode = $findTable->item(0);
        return new Table($this->ws, $this, $tableNode, $this->dom);
    }

    public function get(string $attribute): ?string
    {
        return $this->domNode->getAttribute($attribute);
    }

    /**
     * @return array<string>
     */
    protected function parseTable(\DOMElement $table): array
    {
        $description = $table->getAttribute('description');
        $name = $table->getAttribute('name');
        $url = $table->getAttribute('url');
        return [
            self::KEY_TABLE_NAME => $name,
            self::KEY_TABLE_DESCRIPTION => $description,
            self::KEY_TABLE_URL => $url,
        ];
    }
}

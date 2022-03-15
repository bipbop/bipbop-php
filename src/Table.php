<?php

declare(strict_types=1);

namespace BIPBOP\Client;

use ReflectionException;

class Table
{
    /**
     * @var \DOMElement
     */
    protected $domNode;

    /**
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * @var \DOMXPath
     */
    protected $xpath;

    /**
     * @var WebService
     */
    protected $ws;

    /**
     * @var Database
     */
    protected $database;

    public function __construct(
        WebService   $ws,
        Database     $database,
        \DOMElement  $domNode,
        \DOMDocument $dom
    )
    {
        $this->ws = $ws;
        $this->domNode = $domNode;
        $this->dom = $dom;
        $this->xpath = new \DOMXPath($dom);
        $this->database = $database;
    }

    public function database(): Database
    {
        return $this->database;
    }

    /**
     * Nome da tabela
     */
    public function name(): string
    {
        return $this->domNode->getAttribute('name');
    }

    public function getFields(): \Generator
    {
        $fields = $this->xpath->query('./field', $this->domNode);
        foreach ($fields as $field) {
            yield new Field($this, $this->database, $field, $this->dom);
        }
    }

    /**
     * @param array<string> $parameters
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function generatePush(
        array $parameters,
        ?string $label,
        ?string $pushCallback,
        string $pushClass = Push::class,
        array $tags = []
    ): string {
        $reflection = new \ReflectionClass($pushClass);
        $query = sprintf(
            "SELECT FROM '%s'.'%s'",
            $this->database->name(),
            $this->domNode->getAttribute('name')
        );
        /** @var Push $instance */
        $instance = $reflection->newInstance($this->ws);
        return $instance->create($label, $pushCallback, $query, $parameters, $tags);
    }

    /**
     * Retorna um parâmetro da table
     */
    public function get(string $attribute): ?string
    {
        return $this->domNode->getAttribute($attribute);
    }
}

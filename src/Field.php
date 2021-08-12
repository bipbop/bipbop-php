<?php

declare(strict_types=1);

namespace BIPBOP\Client;

use DOMElement;
use Symfony\Component\VarDumper\Cloner\Data;

/**
 * Informações a respeito de um campo da BIPBOP
 */
class Field
{
    protected \DOMXPath $xpath;
    protected DOMElement $domNode;
    protected \DOMDocument $dom;
    protected Table $table;
    protected Database $database;

    public function __construct(
        Table        $table,
        Database     $database,
        DOMElement   $domNode,
        \DOMDocument $dom
    )
    {
        $this->table = $table;
        $this->database = $database;
        $this->dom = $dom;
        $this->domNode = $domNode;
        $this->xpath = new \DOMXPath($dom);
    }

    public function database(): Database
    {
        return $this->database;
    }

    public function table(): Table
    {
        return $this->table;
    }

    public function get(string $attribute): ?string
    {
        return $this->domNode->getAttribute($attribute);
    }

    /**
     * @return array<array{0: string, 1: string}>
     */
    public function options(): array
    {
        $query = $this->xpath->query('./option', $this->domNode);
        return $this->readOptions($query);
    }

    public function name(): ?string
    {
        return $this->get('name');
    }

    /**
     * @return array<{0: string, 1: array<array{0: string, 1: string}>>
     */
    public function groupOptions(): array
    {
        return array_map(function ($node) {
            $optionNode = $this->xpath->query('./option', $node);
            return [
                $node->getAttribute('value'),
                $this->readOptions($optionNode),
            ];
        }, $this->optionGroup());
    }

    /**
     * @return array<string>
     */
    protected function readOptions(\DOMNodeList $nodeList): array
    {
        return array_map(static function ($node) {
            return [$node->getAttribute('value'), $node->nodeValue];
        }, iterator_to_array($nodeList));
    }

    /**
     * @return array<DOMElement>
     */
    protected function optionGroup(): array
    {
        $query = $this->xpath->query('./optgroup', $this->domNode);
        return iterator_to_array($query);
    }
}

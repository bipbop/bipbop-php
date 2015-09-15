<?php

namespace BIPBOP;

/**
 * Informações a respeito de um campo da BIPBOP
 */
class Field {

    /**
     * Node do field
     * @var DOMNode
     */
    protected $domNode;
    
    /**
     * DOMDocument
     * @var \DOMDocument
     */
    protected $dom;
    
    /**
     * Table do campo
     * @var \BIPBOP\Table
     */
    protected $table;
    
    /**
     * Database do campo
     * @var \BIPBOP\Database
     */
    protected $database;
    
    /**
     * XPath do Documento
     * @var \DOMXPath
     */
    protected $xpath;

    /**
     * Informações a respeito de um campo da BIPBOP
     * @param \BIPBOP\Table $table
     * @param \BIPBOP\Database $database
     * @param \BIPBOP\DOMNode $domNode
     * @param \BIPBOP\DOMDocument $dom
     */
    public function __construct(Table $table, Database $database, DOMNode $domNode, DOMDocument $dom) {
        $this->table = $table;
        $this->database = $database;
        $this->dom = $dom;
        $this->domNode = $domNode;
        $this->xpath = new \DOMXPath($dom);
    }

    /**
     * Informação do XML a respeito de um campo
     * @param string $attribute
     * @return string|null
     */
    public function get($attribute) {
        return $this->domNode->getAttribute($attribute);
    }

    /**
     * Lista de opções disponíveis
     * @param \DOMNodeList $nodeList
     * @return array
     */
    protected function readOptions(\DOMNodeList $nodeList) {
        return array_map(function ($node) {
            return [$node->getAttribute("value"), $node->nodeValue];
        }, iterator_to_array($nodeList));
    }

    /**
     * Lista de opções do campo
     * @return array
     */
    public function getOptions() {
        return $this->readOptions($this->xpath->query("./option", $this->domNode));
    }

    /**
     * Lista de opções do grupo
     * @return string
     */
    public function getGroupOptions() {
        return array_map(function ($node) {
            return [$node->getAttribute("value"),
                $this->readOptions($this->xpath->query("./option", $node))];
        }, iterator_to_array($this->xpath->query("./optgroup", $this->domNode)));
    }

}

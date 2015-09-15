<?php

namespace BIPBOP;

class Database {

    const KEY_TABLE_NAME = "name";
    const KEY_TABLE_DESCRIPTION = "description";
    const KEY_TABLE_URL = "url";

    /**
     * Web Service
     * @var \BIPBOP\WebService
     */
    protected $ws;
    
    /**
     * Database tag
     * @var \DOMNode
     */
    protected $domNode;
    
    /**
     * DOMDocument
     * @var \DOMDocument
     */
    protected $dom;
    
    /**
     * XPath
     * @var \DOMXPath
     */
    protected $xpath;

    /**
     * Instância um Database
     * @param \BIPBOP\WebService $ws
     * @param \DOMNode $domNode
     * @param \DOMDocument $dom
     */
    public function __construct(WebService $ws, \DOMNode $domNode, \DOMDocument $dom) {
        $this->ws = $ws;
        $this->domNode = $domNode;
        $this->dom = $dom;
        $this->xpath = new \DOMXPath($dom);
    }
    
    /**
     * Captura o nome do database
     * @return string
     */
    public function getName() {
        return $this->domNode->getAttribute("name");
    }

    public function listTables() {
        $tables = $this->xpath->query("./table", $this->domNode);
        foreach ($tables as $table) {
            yield [
                self::KEY_TABLE_NAME => $table->getParameter("name"),
                self::KEY_TABLE_DESCRIPTION => $table->getParameter("description"),
                self::KEY_TABLE_URL => $table->getParameter("url")
            ];
        }
    }

    public function getTable($name) {
        $findTable = $this->xpath->query(sprintf("./table[@name='%s']", preg_replace("/[^a-z0-9]/i", "", $name)), $this->dom);
        if (!$findTable) {
            throw new Exception("Can't find that table.");
        }

        $tableNode = $findTable->item(0);
        return new Database($this->ws, $tableNode, $this->listDatabases);        
    }

    public function get($attribute) {
        return $this->domNode->getAttribute($attribute);
    }
    
}

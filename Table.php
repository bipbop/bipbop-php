<?php

namespace BIPBOP;

class Table {

    protected $ws;
    protected $domNode;
    protected $dom;
    protected $xpath;
    protected $database;

    /**
     * Nome do campo
     * @param \BIPBOP\WebService $ws
     * @param \BIPBOP\Database $database
     * @param \DOMNode $domNode
     * @param \DOMDocument $dom
     */
    public function __construct(WebService $ws, Database $database, \DOMNode $domNode, \DOMDocument $dom) {
        $this->ws = $ws;
        $this->domNode = $domNode;
        $this->dom = $dom;
        $this->xpath = new \DOMXPath($dom);
        $this->database = $database;
    }

    /**
     * Nome da tabela
     * @return string
     */
    public function getName() {
        return $this->domNode->getAttribute("name");
    }

    /**
     * Campos
     */
    public function getFields() {
        $fields = $this->xpath->query("./field", $domNode);
        foreach ($fields as $field) {
            yield new Field($this, $this->database, $field, $this->dom);
        }
    }

    /**
     * Cria um PUSH
     * @param array $parameters
     * @param string $label
     * @param string $pushCallback URL de retorno do documento
     * @param string $pushClass Endereço da classe que cuida do PUSH
     * @return \DOMDocument
     */
    public function generatePush(Array $parameters, $label, $pushCallback, $pushClass = "\BIPBOP\DatabasePush") {
        $reflection = new \ReflectionClass($pushClass);
        $query = sprintf("SELECT FROM '%s'.'%s'", $this->database->getName(), $this->dom->getParameter("name"));
        $instance = $reflection->newInstance($this->ws);
        $this->validateParameters($parameters);
        /* @var $instance \BIPBOP\Push */
        return $instance->create($label, $pushCallback, $query, $parameters);
    }

    /**
     * Retorna um parâmetro da table
     * @param string $attribute
     * @return string|null
     */
    public function get($attribute) {
        return $this->domNode->getAttribute($attribute);
    }

}

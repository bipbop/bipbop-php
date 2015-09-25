<?php

namespace BIPBOP\Client;

/**
 * Recebe os parâmetros do PUSH
 */
class Receiver {

    /**
     * Versão do documento
     * @var int
     */
    public $version;

    /**
     * ID do documento
     * @var string
     */
    public $documentId;

    /**
     * Nome do documento
     * @var string
     */
    public $label;

    /**
     * Recebe parâmetros
     */
    public function __construct() {
        $this->version = (int) $this->server("HTTP_X_BIPBOP_VERSION");
        $this->documentId = $this->server("HTTP_X_BIPBOP_DOCUMENT_ID"); /* Organizar os documentos por este ID */
        $this->label = $this->server("HTTP_X_BIPBOP_DOCUMENT_LABEL");
    }

    /**
     * Captura o documento 
     * @return \DOMDocument
     */
    public function document() {
        $rawpost = file_get_contents('php://input');
        $domdocument = new \DOMDocument;
        $domdocument->loadXML($rawpost);
        return $domdocument;
    }
    

    /**
     * Informações do Servidor
     * @param string $idx
     * @param boolean $throwException
     * @param mixed $default
     * @return string
     * @throws Exception
     */
    protected function server($idx, $throwException = true, $default = null) {
        if (!isset($_SERVER[$idx])) {
            if ($throwException) {
                throw new Exception("Required parameter was not received.");
            }
            return $default;
        }
        return (string) $_SERVER[$idx];
    }

}

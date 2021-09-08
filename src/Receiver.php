<?php

declare(strict_types=1);

namespace BIPBOP\Client;

use Psr\Http\Message\RequestInterface;

class Receiver
{
    protected const HEADER_BIPBOP_VERSION = 'HTTP_X_BIPBOP_VERSION';
    protected const HEADER_BIPBOP_DOCUMENT_ID = 'HTTP_X_BIPBOP_DOCUMENT_ID';
    protected const HEADER_BIPBOP_DOCUMENT_LABEL = 'HTTP_X_BIPBOP_DOCUMENT_LABEL';

    /**
     * @var int
     */
    protected $version;

    /**
     * @var string
     */
    protected $documentId;

    /**
     * @var string|null
     */
    protected $label;

    /**
     * @var RequestInterface|null
     */
    protected $request;

    /**
     * @throws Exception
     */
    public function __construct(?RequestInterface $request)
    {
        $this->request = $request;
        $this->version = (int) $this->server(
            self::HEADER_BIPBOP_VERSION,
            true
        );
        $this->documentId = $this->server(
            self::HEADER_BIPBOP_DOCUMENT_ID,
            true
        );
        $this->label = $this->server(
            self::HEADER_BIPBOP_DOCUMENT_LABEL,
            false
        );
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getId(): string
    {
        return $this->documentId;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @throws Exception
     */
    public function document(): \DOMDocument
    {
        $bodyContent = $this->getBodyContent();
        $document = new \DOMDocument();
        $document->preserveWhiteSpace = false;
        $document->formatOutput = false;
        $document->loadXML($bodyContent);
        return $document;
    }

    /**
     * @throws Exception
     */
    protected function server(
        string $headerName,
        bool $throwException = true,
        ?string $default = null
    ): ?string {
        $server = $this->getServerParams();
        if (! isset($server[$headerName])) {
            if ($throwException) {
                throw new Exception("Required parameter '${headerName}' not received.");
            }
            return $default;
        }
        return (string) $server[$headerName];
    }

    /**
     * @return array<string>
     *
     * @throws Exception
     */
    protected function getServerParams(): array
    {
        if ($this->request) {
            return $this->request->getServerParams();
        }
        if (isset($_SERVER)) {
            return $_SERVER;
        }

        throw new Exception('missing server parameters');
    }

    /**
     * @throws Exception
     */
    protected function getBodyContent(): string
    {
        if ($this->request) {
            $body = $this->request->getBody();
            return $body->read($body->getSize());
        }
        $body = file_get_contents('php://input');
        if ($body) {
            return $body;
        }

        throw new Exception('missing body');
    }
}

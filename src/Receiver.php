<?php

declare(strict_types=1);

namespace BIPBOP\Client;

use Psr\Http\Message\ServerRequestInterface;

class Receiver
{
    protected const HEADER_BIPBOP_VERSION = 'HEADER_BIPBOP_VERSION';
    protected const HEADER_BIPBOP_DOCUMENT_ID = 'HEADER_BIPBOP_DOCUMENT_ID';
    protected const HEADER_BIPBOP_DOCUMENT_LABEL = 'HEADER_BIPBOP_DOCUMENT_LABEL';

    protected int $version;

    protected ?string $documentId;
    protected ?string $label;

    protected ServerRequestInterface $request;

    /**
     * @throws Exception
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $this->version = (int) $this->server(
            self::HEADER_BIPBOP_VERSION,
            false
        );
        $this->documentId = $this->server(
            self::HEADER_BIPBOP_DOCUMENT_ID,
            false
        );
        $this->label = $this->server(
            self::HEADER_BIPBOP_DOCUMENT_LABEL,
            false
        );
    }

    public function document(): \DOMDocument
    {
        $body = $this->request->getBody();
        $bodyContent = $body->read($body->getSize());
        $document = new \DOMDocument();
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
        $server = $this->request->getServerParams();
        if (! isset($server[$headerName])) {
            if ($throwException) {
                throw new Exception("Required parameter '${headerName}' not received.");
            }
            return $default;
        }
        return (string) $server[$headerName];
    }
}

<?php

declare(strict_types=1);

namespace BIPBOP\Client;

use Psr\Http\Message\RequestInterface;

class Receiver
{

    protected const HEADER_BIPBOP_COMPANY = 'HTTP_X_BIPBOP_COMPANY';
    protected const HEADER_BIPBOP_APIKEY = 'HTTP_X_BIPBOP_APIKEY';
    protected const HEADER_BIPBOP_TAGS = 'HTTP_X_BIPBOP_TAGS';
    protected const HEADER_BIPBOP_MEMORY_ID = 'HTTP_X_BIPBOP_MEMORY_ID';
    protected const HEADER_BIPBOP_EXCEPTION = 'HTTP_X_BIPBOP_EXCEPTION';

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
     * @var bool
     */
    protected $isException;

    /**
     * @var string|null
     */
    protected $company;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var string|null
     */
    protected $memoryId;

    /**
     * @throws Exception
     */
    public function __construct(?RequestInterface $request)
    {
        $this->request = $request;
        $this->version = (int) $this->server(
            self::HEADER_BIPBOP_VERSION,
        );
        $this->documentId = $this->server(
            self::HEADER_BIPBOP_DOCUMENT_ID,
        );
        $this->label = $this->server(
            self::HEADER_BIPBOP_DOCUMENT_LABEL,
        );

        $this->company = $this->server(
            self::HEADER_BIPBOP_COMPANY,
        );

        $this->apiKey = $this->server(
            self::HEADER_BIPBOP_APIKEY,
        );

        $this->tags = Tags::decode($this->server(
            self::HEADER_BIPBOP_TAGS,
        ));

        $this->memoryId = $this->server(
            self::HEADER_BIPBOP_MEMORY_ID,
        );

        $this->isException = $this->server(
            self::HEADER_BIPBOP_EXCEPTION,
        ) === "true";
    }

    public function getCompany(): string
    {
        return $this->company;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }


    public function getTags(): array
    {
        return $this->tags;
    }


    public function getMemoryId(): string
    {
        return $this->memoryId;
    }

    public function isException(): bool
    {
        return $this->isException;
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

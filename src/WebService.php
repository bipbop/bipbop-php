<?php

declare(strict_types=1);

namespace BIPBOP\Client;

use DOMDocument;

class WebService
{
    protected const FREE_APIKEY = '6057b71263c21e4ada266c9d4d4da613';
    protected const ENDPOINT = 'https://irql.bipbop.com.br/';
    protected const PARAMETER_QUERY = 'q';
    protected const PARAMETER_APIKEY = 'apiKey';

    protected string $apiKey;

    /**
     * @var resource
     */
    protected $resource;

    public function __construct(?string $apiKey = null)
    {
        $apiKey = $apiKey ? $apiKey : self::FREE_APIKEY;
        $this->resource = curl_init(self::ENDPOINT);
        $this->apiKey = $apiKey;

        curl_setopt_array($this->resource, [
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => 'gzip',
        ]);
    }

    public function __destruct()
    {
        curl_close($this->resource);
    }

    /**
     * @param array<string> $parameters
     * @throws Exception
     * @return DOMDocument | string
     */
    public function post(
        string $query,
        array $parameters = [],
        bool $parseDocument = true
    ) {
        curl_setopt_array($this->resource, [
            CURLOPT_POSTFIELDS => array_merge($parameters, [
                self::PARAMETER_QUERY => $query,
                self::PARAMETER_APIKEY => $this->apiKey,
            ]),
        ]);

        $dom = new DOMDocument();
        $ret = curl_exec($this->resource);
        if ($ret === false) {
            throw new Exception(curl_error($this->resource));
        }

        if (! is_string($ret)) {
            throw new \LogicException("curl returned a unknown type");
        }

        if (! $parseDocument) {
            return $ret;
        }

        $dom->loadXML($ret);
        $this->assert($dom);
        return $dom;
    }

    /**
     * @throws Exception
     */
    public function assert(DOMDocument $dom): void
    {
        $queryNode = (new \DOMXPath($dom))->query('/BPQL/header/exception');
        if (! $queryNode->length) {
            return;
        }

        $nodeException = $queryNode->item(0);
        if (! $nodeException instanceof \DOMElement) {
            return;
        }
        throw $this->exceptionFromElement($nodeException);
    }

    protected function isPushable(\DOMElement $nodeException): bool
    {
        return ($nodeException->hasAttribute('pushable') ?
            $nodeException->getAttribute('pushable') :
            $nodeException->getAttribute('push')) === 'true';
    }

    protected function exceptionFromElement(\DOMElement $nodeException): Exception
    {
        $source = $nodeException->getAttribute('source');
        $code = (int) $nodeException->getAttribute('code');
        $id = $nodeException->getAttribute('id');
        $pushable = $this->isPushable($nodeException);
        $message = $nodeException->nodeValue;
        $e = new Exception($message, $code);
        $e->configure(
            $code,
            $source,
            $id,
            $message,
            $pushable
        );
        return $e;
    }
}

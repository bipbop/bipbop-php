<?php

declare(strict_types=1);

namespace BIPBOP\Client;

abstract class ProviderServiceDiscovery
{
    protected const KEY_DATABASE_NAME = 'name';
    protected const KEY_DATABASE_DESCRIPTION = 'description';
    protected const KEY_DATABASE_URL = 'url';

    /**
     * @var WebService
     */
    protected $ws;

    /**
     * @var \DOMDocument
     */
    protected $listDatabases;

    /**
     * @var \DOMXPath
     */
    protected $xpath;

    public function __construct(
        WebService $ws,
        \DOMDocument $databases
    ) {
        $this->ws = $ws;
        $this->listDatabases = $databases;
        $this->xpath = new \DOMXPath($this->listDatabases);
    }

    public function listDatabases(): \Generator
    {
        $databases = $this->xpath->query('/BPQL/body/database');
        foreach ($databases as $database) {
            $name = $database->getAttribute('name');
            $description = $database->getAttribute('description');
            $url = $database->getAttribute('url');
            yield [
                self::KEY_DATABASE_NAME => $name,
                self::KEY_DATABASE_DESCRIPTION => $description,
                self::KEY_DATABASE_URL => $url,
            ];
        }
    }

    /**
     * @throws Exception
     */
    public function getDatabase(string $name): Database
    {
        $findDatabase = $this->xpath->query(sprintf(
            "/BPQL/body/database[@name='%s']",
            preg_replace('/[^a-z0-9]/i', '', $name)
        ));
        if (! $findDatabase->length) {
            throw new Exception("can't find that database '${name}'");
        }

        $databaseNode = $findDatabase->item(0);

        return new Database(
            $this->ws,
            $databaseNode,
            $this->listDatabases
        );
    }
}

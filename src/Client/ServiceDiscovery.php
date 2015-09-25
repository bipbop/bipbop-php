<?php

namespace BIPBOP\Client;

class ServiceDiscovery {

    protected $ws;
    protected $listDatabases;
    protected $xpath;

    const KEY_DATABASE_NAME = "name";
    const KEY_DATABASE_DESCRIPTION = "description";
    const KEY_DATABASE_URL = "url";

    protected function __construct(WebService $ws, \DOMDocument $databases) {
        $this->ws = $ws;
        $this->listDatabases = $databases;
        $this->xpath = new \DOMXPath($this->listDatabases);
    }

    public static function factory(WebService $ws, Array $parameters = []) {
        return new self($ws, $ws->post("SELECT FROM 'INFO'.'INFO'", $parameters));
    }

    public function listDatabases() {
        $databases = $this->xpath->query("/BPQL/body/database");
        foreach ($databases as $database) {
            yield [
                self::KEY_DATABASE_NAME => $database->getAttribute("name"),
                self::KEY_DATABASE_DESCRIPTION => $database->getAttribute("description"),
                self::KEY_DATABASE_URL => $database->getAttribute("url")
            ];
        }
    }

    public function getDatabase($name) {
        $findDatabase = $this->xpath->query(sprintf("/BPQL/body/database[@name='%s']", preg_replace("/[^a-z0-9]/i", "", $name)));
        if (!$findDatabase->length) {
            throw new Exception("Can't find that database.");
        }

        $databaseNode = $findDatabase->item(0);

        return new Database($this->ws, $databaseNode, $this->listDatabases);
    }

}

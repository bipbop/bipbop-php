<?php

namespace BIPBOP;

class ServiceDiscovery {

    protected $ws;
    protected $listDatabases;
    protected $xpath;

    const KEY_DATABASE_NAME = "name";
    const KEY_DATABASE_DESCRIPTION = "description";
    const KEY_DATABASE_URL = "url";

    private function __construct(WebService $ws, \DOMDocument $databases) {
        $this->ws = $ws;
        $this->listDatabases = $databases;
        $this->xpath = new \DOMXPath($this->listDatabases);
    }

    public static function factory(WebService $ws, Array $parameters = []) {
        return new self($ws, $this->ws->post("SELECT FROM 'INFO'.'INFO'", $parameters));
    }

    public function listDatabases() {
        $databases = $this->xpath->query("/BPQL/body/database");
        foreach ($databases as $database) {
            yield [
                self::KEY_DATABASE_NAME => $database->getParameter("name"),
                self::KEY_DATABASE_DESCRIPTION => $database->getParameter("description"),
                self::KEY_DATABASE_URL => $database->getParameter("url")
            ];
        }
    }

    public function getDatabase($name) {
        $findDatabase = $this->xpath->query(sprintf("/BPQL/body/database[@name='%s']", preg_replace("/[^a-z0-9]/i", "", $name)));
        if (!$findDatabase) {
            throw new Exception("Can't find that database.");
        }

        $databaseNode = $findDatabase->item(0);

        return new Database($this->ws, $databaseNode, $this->listDatabases);
    }

}

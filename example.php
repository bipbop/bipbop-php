<?php

require "vendor/autoload.php";

$webService = new \BIPBOP\Client\WebService(/* Coloque sua chave de API aqui */);
$serviceDiscovery = \BIPBOP\Client\ServiceDiscovery::factory($webService);


printf("\n\n== Listando todos os databases ==\n\n");
foreach ($serviceDiscovery->listDatabases() as $databaseInformation) {
    /* @var $database \BIPBOP\Client\Database */
    $database = $serviceDiscovery->getDatabase($databaseInformation["name"]);
    printf("Available Database: %s\nDescription: %s\nURL: %s\n\n", $database->name(), $database->get("description"), $database->get("url"));
}

$databasePlaca = $serviceDiscovery->getDatabase("PLACA");
printf("\n== Listando tabelas de PLACA ==\n\n");
foreach ($databasePlaca->listTables() as $tableInformation) {
    /* @var $database \BIPBOP\Client\Database */
    $table = $databasePlaca->getTable($tableInformation["name"]);
    printf("Available Table: %s\nDescription: %s\nURL: %s\n\n", $table->name(), $table->get("description"), $table->get("url"));
}

$tableConsulta = $databasePlaca->getTable("CONSULTA");
printf("\n== Listando campos de CONSULTA ==\n\n");
foreach ($tableConsulta->getFields() as $field) {
	printf("Available Field: %s\n\n", $field->name());
}

$dom = $webService->post("SELECT FROM 'PLACA'.'CONSULTA'", [
    "placa" => "OGD1557"
]);
printf($dom->saveXML());

$xpath = new \DOMXpath($dom);
printf($xpath->evaluate("string(/BPQL/body/marca/.)"));
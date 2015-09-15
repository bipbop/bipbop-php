<?php

require('Exception.php');
require('WebService.php');
require('ServiceDiscovery.php');
require('Push.php');
require('PushJuristek.php');
require('Table.php');
require('Database.php');
require('ServiceDiscoveryJuristek.php');
require('Field.php');

$serviceDiscovery = \BIPBOP\ServiceDiscovery::factory(new \BIPBOP\WebService("6057b71263c21e4ada266c9d4d4da613"), [
            \BIPBOP\ServiceDiscoveryJuristek::PARAMETER_OAB => true
        ]);

foreach ($serviceDiscovery->listDatabases() as $databaseDescription) {
    $database = $serviceDiscovery->getDatabase($databaseDescription[\BIPBOP\ServiceDiscoveryJuristek::KEY_DATABASE_NAME]);
    /* @var $field \BIPBOP\Database */
    foreach ($database->listTables() as $tableDescription) {
        $table = $database->getTable($tableDescription[BIPBOP\Database::KEY_TABLE_NAME]);
        /* @var $field \BIPBOP\Table */
        foreach ($table->getFields() as $field) {
            /* @var $field \BIPBOP\Field */
            var_dump($field->getName());
        }
    }
}
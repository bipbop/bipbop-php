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
require('Receiver.php');

$receiver = new \BIPBOP\Receiver;

var_export([
    $receiver->documentId,
    $receiver->label,
    $receiver->version
]);

echo \BIPBOP\WebService::assert($receiver->document());
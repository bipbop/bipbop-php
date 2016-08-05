<?php
use PHPUnit\Framework\TestCase;

spl_autoload_register(function ($class_name) {
    include str_replace('BIPBOP\\', 'src\\', $class_name) . '.php';
});

class BipbopTest extends TestCase
{
    private $webService;
    private $serviceDiscovery;
    
    public function __construct() {
        $this->webService = new \BIPBOP\Client\WebService(/* sua chave aqui */);
        $this->serviceDiscovery = \BIPBOP\Client\ServiceDiscovery::factory($this->webService);
    }

    public function testBasicWebservice() {
        $dom = $this->webService->post("SELECT FROM 'PLACA'.'CONSULTA'", [
            "placa" => "OGD1557"
        ]);
        $this->assertNotNull($dom);
    }

    public function testListDatabase() {
        $this->assertTrue(count($this->serviceDiscovery->listDatabases()) > 0);
    }

    public function testDbName() {
        $this->assertEquals('PLACA', $this->serviceDiscovery->getDatabase("PLACA")->name());
    }

    public function testTableName() {
        $dbPlaca = $this->serviceDiscovery->getDatabase("PLACA");
        $this->assertEquals('CONSULTA', $dbPlaca->getTable("CONSULTA")->name());
    }

    public function testFieldName() {
        $dbPlaca = $this->serviceDiscovery->getDatabase("PLACA");
        $table = $dbPlaca->getTable("CONSULTA");
        $this->assertEquals('placa', iterator_to_array($table->getFields())[0]->name());
    }

    public function testTraverseDb() {
        foreach ($this->serviceDiscovery->listDatabases() as $db) {
            $this->assertNotNull($db['name']);
            $odb = $this->serviceDiscovery->getDatabase($db['name']);
            $this->assertNotNull($odb);
            foreach ($odb->listTables() as $table) {
                $this->assertNotNull($table['name']);
                $otb = $odb->getTable($table['name']);
                $this->assertNotNull($otb);
                foreach ($otb->getFields() as $field) {
                    $this->assertNotNull($field->name());
                }
            }
        }
    }
}
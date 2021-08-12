<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\ServiceDiscovery;
use BIPBOP\Client\WebService;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * Class ServiceDiscoveryTest.
 *
 * @covers \BIPBOP\Client\ProviderServiceDiscovery
 * @covers \BIPBOP\Client\ServiceDiscovery
 */
class ServiceDiscoveryTest extends TestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->serviceDiscovery);
    }

    public function testFactory(): void
    {
        $domDocument = new \DOMDocument();
        $domDocument->loadXML(file_get_contents(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'mock',
            'info.xml'
        ])));

        $webService = \Mockery::mock(WebService::class);
        $webService->shouldReceive("post")
            ->with("SELECT FROM 'INFO'.'INFO'", [])
            ->once()
            ->andReturn($domDocument);
        $serviceDiscovery = ServiceDiscovery::factory($webService);
        $this->assertEquals([[
            'name' => 'CORREIOS',
            'description' => 'Empresa Brasileira de Correios e Telégrafos ou, simplesmente, Correios, é uma empresa pública federal responsável pela execução do sistema de envio e entrega de correspondências no Brasil',
            'url' => 'http://www.correios.com.br/',
        ], [
            'name' => 'RAIS',
            'description' => 'RAIS - Consulta Relação Anual de Informações Sociais',
            'url' => 'http://www.rais.gov.br/sitio/consulta_trabalhador_identificacao.jsf',
        ]], iterator_to_array($serviceDiscovery->listDatabases()));
    }
}

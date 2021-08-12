<?php

namespace Tests\Unit\BIPBOP\Client;

use BIPBOP\Client\Database;
use BIPBOP\Client\Exception;
use BIPBOP\Client\ProviderServiceDiscovery;
use BIPBOP\Client\WebService;
use PHPUnit\Framework\TestCase;

/**
 * Class ProviderServiceDiscoveryTest.
 *
 * @covers \BIPBOP\Client\ProviderServiceDiscovery
 */
class ProviderServiceDiscoveryTest extends TestCase
{
    /**
     * @var ProviderServiceDiscovery
     */
    protected $providerServiceDiscovery;
    /**
     * @var WebService|\Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    private $webService;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();


        $this->webService = \Mockery::mock(WebService::class);
        $this->dom = new \DOMDocument();
        $this->dom->loadXML(file_get_contents(implode(DIRECTORY_SEPARATOR, [
            __DIR__,
            '..',
            'mock',
            'info.xml'
        ])));

        /** @todo Correctly instantiate tested object to use it. */
        $this->providerServiceDiscovery = $this->getMockBuilder(ProviderServiceDiscovery::class)
            ->setConstructorArgs([$this->webService, $this->dom])
            ->getMockForAbstractClass();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->providerServiceDiscovery);
    }

    public function testListDatabases(): void
    {
        $this->assertEquals([[
            'name' => 'CORREIOS',
            'description' => 'Empresa Brasileira de Correios e Telégrafos ou, simplesmente, Correios, é uma empresa pública federal responsável pela execução do sistema de envio e entrega de correspondências no Brasil',
            'url' => 'http://www.correios.com.br/',
        ], [
            'name' => 'RAIS',
            'description' => 'RAIS - Consulta Relação Anual de Informações Sociais',
            'url' => 'http://www.rais.gov.br/sitio/consulta_trabalhador_identificacao.jsf',
        ]], iterator_to_array($this->providerServiceDiscovery->listDatabases()));
    }

    public function testGetDatabase(): void
    {
        $this->assertInstanceOf(Database::class, $this->providerServiceDiscovery->getDatabase("CORREIOS"));
        $this->expectException(Exception::class);
        $this->providerServiceDiscovery->getDatabase("INVALID_DATABASE");
    }
}

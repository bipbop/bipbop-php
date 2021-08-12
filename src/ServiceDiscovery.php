<?php

declare(strict_types=1);

namespace BIPBOP\Client;

class ServiceDiscovery extends ProviderServiceDiscovery
{
    /**
     * @param array<string> $parameters
     *
     * @throws Exception
     */
    public static function factory(
        WebService $ws,
        array $parameters = []
    ): self {
        return new self(
            $ws,
            $ws->post("SELECT FROM 'INFO'.'INFO'", $parameters)
        );
    }
}

<?php

declare(strict_types=1);

namespace BIPBOP\Client;

class ServiceDiscoveryJuristek extends ProviderServiceDiscovery
{
    public const PARAMETER_OAB = 'OAB';

    /**
     * @param array<string> $parameters
     *
     * @throws Exception
     */
    public static function factory(WebService $ws, array $parameters = []): self
    {
        return new self(
            $ws,
            $ws->post(
                "SELECT FROM 'JURISTEK'.'INFO'",
                self::generateParameters($parameters)
            )
        );
    }

    /**
     * @param array<string> $parameters
     *
     * @return array<string>
     */
    protected static function generateParameters(array $parameters): array
    {
        return array_merge(
            $parameters,
            [
                'data' => self::generatePayload($parameters),
            ]
        );
    }

    /**
     * @param array<string> $parameters
     */
    protected static function generatePayload(array $parameters): string
    {
        return self::hasParameterOAB($parameters) ?
            "SELECT FROM 'INFO'.'INFO' WHERE 'TIPO_CONSULTA' = 'OAB'" :
            "SELECT FROM 'INFO'.'INFO'";
    }

    /**
     * @param array<string> $parameters
     */
    protected static function hasParameterOAB(array $parameters): bool
    {
        return isset($parameters[self::PARAMETER_OAB]) &&
            $parameters[self::PARAMETER_OAB];
    }
}

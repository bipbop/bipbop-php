<?php

namespace BIPBOP\Client;

class ServiceDiscoveryJuristek extends ServiceDiscovery {

    const PARAMETER_OAB = "OAB";

    public static function factory(WebService $ws, Array $parameters = []) {
        return new self($ws, $ws->post("SELECT FROM 'JURISTEK'.'INFO'", array_merge($parameters, [
                    "data" => isset($parameters[self::PARAMETER_OAB]) && $parameters[self::PARAMETER_OAB] ?
                            "SELECT FROM 'INFO'.'INFO' WHERE 'TIPO_CONSULTA' = 'OAB'" : "SELECT FROM 'INFO'.'INFO'"
        ])));
    }

}

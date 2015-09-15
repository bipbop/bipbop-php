<?php

namespace BIPBOP;

/**
 * Web Service - Implementação do BIPBOP Push
 * @author Desenvolvimento <desenvolvimento@bipbop.com.br>
 */
class PushJuristek extends Push {

    const PARAMETER_PUSH_JURISTEK_CALLBACK = "pushJuristekCallback";
    const PARAMETER_PUSH_JURISTEK_QUERY = "pushJuristekCallback";
    
    /**
     * Cria um novo PUSH
     * @param string $label
     * @param string $pushCallback
     * @param string $query
     * @param array $parameters
     * @return string Identificador do PUSH
     */
    public function create($label, $pushCallback, $query, $parameters) {
        $this->webService->post("INSERT INTO 'PUSH'.'JOB'", array_merge($parameters, [
            self::PARAMETER_PUSH_LABEL => $label,
            self::PARAMETER_PUSH_QUERY => "SELECT FROM 'JURISTEK'.'JURISTEK'",
            self::PARAMETER_PUSH_JURISTEK_QUERY => $query,
            self::PARAMETER_PUSH_JURISTEK_CALLBACK => $pushCallback
        ]));
    }

}

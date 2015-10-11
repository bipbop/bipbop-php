<?php

namespace BIPBOP\Client;

/**
 * Web Service - Implementação do BIPBOP Push
 * @author Desenvolvimento <desenvolvimento@bipbop.com.br>
 */
class Push {

    const PARAMETER_PUSH_QUERY = "pushQuery";
    const PARAMETER_PUSH_INTERVAL = "pushInterval";
    const PARAMETER_JURISTEK_CALLBACK = "juristekCallback";
    const PARAMETER_PUSH_LABEL = "pushLabel";
    const PARAMETER_PUSH_AT = "pushAt"; /* timestamp */
    const PARAMETER_PUSH_TRY_IN = "pushTryIn";
    const PARAMETER_PUSH_MAX_VERSION = "pushMaxVersion";
    const PARAMETER_PUSH_EXPIRE = "pushExpire";
    const PARAMETER_PUSH_PRIORITY = "pushPriority";
    const PARAMETER_PUSH_ID = "id";
    const PARAMETER_PUSH_CALLBACK = "pushCallback";

    /**
     * WS da BIPBOP
     * @var \BIPBOP\WebService
     */
    protected $webService;

    /**
     * CRUD do PUSH
     * @param \BIPBOP\WebService $webService
     */
    public function __construct(WebService $webService) {
        $this->webService = $webService;
    }

    /**
     * Cria um novo PUSH
     * @param string $label
     * @param string $pushCallback
     * @param string $query
     * @param array $parameters
     * @return string Identificador do PUSH
     */
    public function create($label, $pushCallback, $query, $parameters) {
        return $this->webService->post("INSERT INTO 'PUSH'.'JOB'", array_merge($parameters, [
            self::PARAMETER_PUSH_LABEL => $label,
            self::PARAMETER_PUSH_QUERY => $query,
            self::PARAMETER_PUSH_CALLBACK => $pushCallback
        ]));
    }

    /**
     * Remove um PUSH
     * @param type $id
     * @return id
     */
    public function delete($id) {
        return (new \DOMXPath($this->webService->post("DELETE FROM 'PUSH'.'JOB'", [
                    "id" => $id
                ])))->evaluate("string(/BPQL/body/id)");
    }

    /**
     * Abre um documento criado
     * @param string $id
     * @param string $label
     */
    public function open($id, $label = null) {
        return $this->webService->post("SELECT FROM 'PUSH'.'DOCUMENT'", array_filter([
            "id" => $id,
            "label" => $label
        ]));
    }
    
    /**
     * Muda o intervalo do PUSH
     * @param type $id
     * @param type $interval
     */
    public function changeInterval($id, $interval) {
        return $this->webService->post("UPDATE 'PUSH'.'PUSHINTERVAL'", [
            self::PARAMETER_PUSH_ID => $id,
            self::PARAMETER_PUSH_INTERVAL => $interval
        ]);
    }
    
    /**
     * Muda a versão máxima do PUSH
     * @param type $id
     * @param type $maxVersion
     */
    public function changeMaxVersion($id, $maxVersion) {
        return $this->webService->post("UPDATE 'PUSH'.'PUSHMAXVERSION'", [
            self::PARAMETER_PUSH_ID => $id,
            self::PARAMETER_PUSH_MAX_VERSION => $maxVersion
        ]);
    }

}

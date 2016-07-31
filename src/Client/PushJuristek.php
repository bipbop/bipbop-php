<?php

namespace BIPBOP\Client;

/**
 * Web Service - Implementação do BIPBOP Push
 * @author Desenvolvimento <desenvolvimento@bipbop.com.br>
 */
class PushJuristek extends Push {

    const PARAMETER_PUSH_JURISTEK_CALLBACK = "juristekCallback";
    const PARAMETER_PUSH_JURISTEK_QUERY = "data";

    /**
     * Cria um novo PUSH
     * @param string $label
     * @param string $pushCallback
     * @param string $query
     * @param array $parameters
     * @return string Identificador do PUSH
     */
    public function create($label, $pushCallback, $query, $parameters) {

        if (!empty($parameters)) {
            $data = [];
            foreach ($parameters as $key => $value) {
                $data[] = call_user_func_array("sprintf", array_merge((array) "'%s' = '%s'", array_map(function ($str) {
                                    return preg_replace("/\'/", "", $str);
                                }, [$key, $value])));
            }
            $query .= (stripos($query, "WHERE") !== FALSE ? " " : " WHERE ") . implode(" AND ", $data);
        }

        return new DOMXPath($this->webService->post("INSERT INTO 'PUSHJURISTEK'.'JOB'", array_merge($parameters, [
            self::PARAMETER_PUSH_LABEL => $label,
            self::PARAMETER_PUSH_QUERY => "SELECT FROM 'JURISTEK'.'PUSH'",
            self::PARAMETER_PUSH_JURISTEK_QUERY => $query,
            self::PARAMETER_PUSH_JURISTEK_CALLBACK => $pushCallback
        ])))->evaluate("/BPQL/body/id");
    }

}

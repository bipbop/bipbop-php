<?php

namespace BIPBOP\Client;

class NameByCPFCNPJ {

    public static function evaluate($cpfCnpj, $birtyday = null) {

        $cpf = new \CpfCnpjValidation\Cpf();
        $cnpj = new \CpfCnpjValidation\Cnpj();

        if ($cpf->isValid($cpfCnpj)) {
            if (!$birtyday)
                throw new Exception("É necessário informar a data de nascimento para consultar um CPF.", Exception::INVALID_ARGUMENT);
        }
        elseif (!$cnpj->isValid($cpfCnpj)) {
            throw new Exception("O documento informado não é um CPF ou CNPJ válido.", Exception::INVALID_ARGUMENT);
        }

        if ($birtyday !== null) {
            if (is_int($birtyday)) {
                $birtyday = date("d/m/Y", $birtyday);
            } elseif ($birtyday instanceof DateTime) {
                $birtyday = $birtyday->format("d/m/Y");
            }
        }

        return (new \DOMXPath((new WebService())->post("SELECT FROM 'BIPBOPJS'.'CPFCNPJ'", [
            "documento" => $cpfCnpj,
            "nascimento" => $birtyday
        ])))->evaluate("string(/BPQL/body/nome/.)");
    }

}

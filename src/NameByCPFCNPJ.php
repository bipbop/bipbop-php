<?php

declare(strict_types=1);

namespace BIPBOP\Client;

use DateTime;

class NameByCPFCNPJ
{
    /**
     * @param string|int|DateTime|null $birthday
     *
     * @throws Exception
     */
    public static function evaluate(
        string $document,
        $birthday = null,
        ?WebService $webService = null
    ): string {
        if ($birthday !== null) {
            if (is_int($birthday)) {
                $birthday = date('d/m/Y', $birthday);
            } elseif ($birthday instanceof DateTime) {
                $birthday = $birthday->format('d/m/Y');
            }
        }

        return (new \DOMXPath(($webService ? $webService : new WebService())->post(
            "SELECT FROM 'BIPBOPJS'.'CPFCNPJ'",
            [
                'documento' => $document,
                'nascimento' => $birthday,
            ]
        )))->evaluate('string(/BPQL/body/nome/.)');
    }
}

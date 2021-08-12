<?php

declare(strict_types=1);

namespace BIPBOP\Client;

class PushJuristek extends ProviderPush
{
    public const PARAMETER_PUSH_JURISTEK_CALLBACK = 'juristekCallback';
    public const PARAMETER_PUSH_JURISTEK_QUERY = 'data';

    /**
     * @param array<string> $parameters
     *
     * @throws Exception
     */
    public function create(
        ?string $label,
        ?string $pushCallback,
        string $query,
        array $parameters
    ): string {
        if (count($parameters)) {
            $query = $this->buildQuery($parameters, $query);
        }

        return (new \DOMXPath($this->webService->post(
            "INSERT INTO 'PUSHJURISTEK'.'JOB'",
            array_merge($parameters, array_filter([
                self::PARAMETER_PUSH_LABEL => $label,
                self::PARAMETER_PUSH_QUERY => "SELECT FROM 'JURISTEK'.'PUSH'",
                self::PARAMETER_PUSH_JURISTEK_QUERY => $query,
                self::PARAMETER_PUSH_JURISTEK_CALLBACK => $pushCallback,
            ]))
        )))->evaluate('string(/BPQL/body/id)');
    }

    /**
     * @param array<string> $parameters
     */
    protected function buildQuery(array $parameters, string $query): string
    {
        $data = [];
        foreach ($parameters as $key => $value) {
            $data[] = $this->dictFormat($key, $value);
        }
        $query .= (stripos($query, 'WHERE') !== false ? ' ' : ' WHERE ')
            . implode(' AND ', $data);
        return $query;
    }

    protected function dictFormat(string $key, string $value): string
    {
        return call_user_func_array(
            'sprintf',
            array_merge((array) "'%s' = '%s'", array_map(
                static function ($str) {
                    return preg_replace("/\'/", '', $str);
                },
                [$key, $value]
            ))
        );
    }
}

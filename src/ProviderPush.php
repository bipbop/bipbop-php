<?php

declare(strict_types=1);

namespace BIPBOP\Client;

abstract class ProviderPush
{
    public const PARAMETER_PUSH_ID = 'id';
    public const PARAMETER_PUSH_QUERY = 'pushQuery';
    public const PARAMETER_PUSH_INTERVAL = 'pushInterval';
    public const PARAMETER_PUSH_LABEL = 'pushLabel';
    public const PARAMETER_PUSH_AT = 'pushAt';
    public const PARAMETER_PUSH_TRY_IN = 'pushTryIn';
    public const PARAMETER_PUSH_MAX_VERSION = 'pushMaxVersion';
    public const PARAMETER_PUSH_EXPIRE = 'pushExpire';
    public const PARAMETER_PUSH_PRIORITY = 'pushPriority';
    public const PARAMETER_PUSH_CALLBACK = 'pushCallback';

    /**
     * @var WebService
     */
    protected $webService;

    public function __construct(WebService $webService)
    {
        $this->webService = $webService;
    }

    /**
     * @param array<string> $parameters
     *
     * @throws Exception
     */
    public function create(
        string $label,
        ?string $pushCallback,
        string $query,
        array $parameters
    ): string {
        return (new \DOMXPath($this->webService->post(
            "INSERT INTO 'PUSH'.'JOB'",
            array_merge($parameters, [
                self::PARAMETER_PUSH_LABEL => $label,
                self::PARAMETER_PUSH_QUERY => $query,
                self::PARAMETER_PUSH_CALLBACK => $pushCallback,
            ])
        )))->evaluate('string(/BPQL/body/id)');
    }

    /**
     * @throws Exception
     */
    public function delete(string $id): \DOMXPath
    {
        return new \DOMXPath($this->webService->post(
            "DELETE FROM 'PUSH'.'JOB'",
            [
                'id' => $id,
            ]
        ));
    }

    /**
     * @throws Exception
     */
    public function open(string $id, ?string $label = null): \DOMDocument
    {
        return $this->webService->post(
            "SELECT FROM 'PUSH'.'DOCUMENT'",
            array_filter([
                'id' => $id,
                'label' => $label,
            ])
        );
    }

    /**
     * @throws Exception
     */
    public function changeInterval(
        string $id,
        ?int $interval = null
    ): \DOMDocument {
        if (! $interval) {
            $interval = strtotime('+1 day', 0);
        }
        return $this->webService->post(
            "UPDATE 'PUSH'.'PUSHINTERVAL'",
            [
                self::PARAMETER_PUSH_ID => $id,
                self::PARAMETER_PUSH_INTERVAL => $interval,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function changeMaxVersion(
        string $id,
        int $maxVersion = 0
    ): \DOMDocument {
        return $this->webService->post(
            "UPDATE 'PUSH'.'PUSHMAXVERSION'",
            [
                self::PARAMETER_PUSH_ID => $id,
                self::PARAMETER_PUSH_MAX_VERSION => $maxVersion,
            ]
        );
    }
}

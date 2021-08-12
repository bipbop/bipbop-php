<?php

declare(strict_types=1);

namespace BIPBOP\Client;

/**
 * BIPBOP Exception
 */
class Exception extends \Exception
{
    /**
     * @var int|null
     */
    protected $providerCode;

    /**
     * @var string|null
     */
    protected $providerSource;

    /**
     * @var string|null
     */
    protected $providerId;

    /**
     * @var string|null
     */
    protected $providerMessage;

    /**
     * @var bool
     */
    protected $providerPushable;

    public function providerCode(): ?int
    {
        return $this->providerCode;
    }

    public function providerSource(): ?string
    {
        return $this->providerSource;
    }

    public function providerId(): ?string
    {
        return $this->providerId;
    }

    public function providerMessage(): ?string
    {
        return $this->providerMessage;
    }

    public function providerPushable(): bool
    {
        return $this->providerPushable;
    }

    /**
     * @internal
     */
    public function configure(
        ?int $code,
        ?string $source,
        ?string $id,
        ?string $message,
        bool $pushable
    ): void {
        $this->providerCode = $code;
        $this->providerSource = $source;
        $this->providerId = $id;
        $this->providerMessage = $message;
        $this->providerPushable = $pushable;
    }
}

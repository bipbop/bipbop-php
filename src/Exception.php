<?php

declare(strict_types=1);

namespace BIPBOP\Client;

/**
 * BIPBOP Exception
 */
class Exception extends \Exception
{
    public const INVALID_ARGUMENT = 1;

    protected ?int $providerCode;
    protected ?string $providerSource;
    protected ?string $providerId;
    protected ?string $providerMessage;
    protected bool $providerPushable;

    public function providerCode(): ?int
    {
        return $this->bipbopCode;
    }

    public function providerSource(): ?string
    {
        return $this->bipbopSource;
    }

    public function providerId(): ?string
    {
        return $this->bipbopId;
    }

    public function providerMessage(): ?string
    {
        return $this->bipbopMessage;
    }

    public function providerPushable(): bool
    {
        return $this->bipbopPushable;
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
        $this->bipbopCode = $code;
        $this->bipbopSource = $source;
        $this->bipbopId = $id;
        $this->bipbopMessage = $message;
        $this->bipbopPushable = $pushable;
    }
}

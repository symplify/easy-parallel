<?php

declare(strict_types=1);

namespace Symplify\EasyParallel\ValueObject;

use Exception;

class ChildProcessTimeoutException extends Exception
{
    /**
     * @var mixed[]
     */
    private array $context = [];

    /**
     * @param mixed[] $context
     */
    public function __construct(
        string $message = '',
        array $context = [],
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * @return mixed[]
     */
    public function getContext(): array
    {
        return $this->context;
    }
}

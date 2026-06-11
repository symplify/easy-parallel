<?php

declare(strict_types=1);

namespace Symplify\EasyParallel\Enum;

/**
 * @api
 */
final class ReactEvent
{
    /**
     * @var string
     */
    public const string EXIT = 'exit';

    /**
     * @var string
     */
    public const string DATA = 'data';

    /**
     * @var string
     */
    public const string ERROR = 'error';

    /**
     * @var string
     */
    public const string CONNECTION = 'connection';
}

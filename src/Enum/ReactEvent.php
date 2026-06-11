<?php

declare(strict_types=1);

namespace Symplify\EasyParallel\Enum;

/**
 * @api
 */
final class ReactEvent
{
    public const string EXIT = 'exit';

    public const string DATA = 'data';

    public const string ERROR = 'error';

    public const string CONNECTION = 'connection';
}

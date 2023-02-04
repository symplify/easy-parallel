<?php

declare(strict_types=1);

use Symplify\EasyParallel\Contract\SerializableInterface;
use Symplify\EasyCI\Config\EasyCIConfig;

return static function (EasyCIConfig $easyCIConfig): void {
    $easyCIConfig->typesToSkip([
        SerializableInterface::class,
    ]);
};

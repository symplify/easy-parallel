<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(codeQuality: true, deadCode: true, codingStyle: true, naming: true, privatization: true, earlyReturn: true)
    ->withPaths([
        __DIR__ . '/config',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withImportNames(removeUnusedImports: true)
    ->withPhpSets()
    ->withSkip([
        '*/Source/*',
    ]);

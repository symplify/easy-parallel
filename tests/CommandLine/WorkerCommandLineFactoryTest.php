<?php

declare(strict_types=1);

namespace Symplify\EasyParallel\Tests\CommandLine;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symplify\EasyParallel\CommandLine\WorkerCommandLineFactory;
use Symplify\EasyParallel\Tests\CommandLine\Source\TestOption;

final class WorkerCommandLineFactoryTest extends TestCase
{
    private const string DUMMY_MAIN_SCRIPT = 'main_script';

    private WorkerCommandLineFactory $workerCommandLineFactory;

    protected function setUp(): void
    {
        $this->workerCommandLineFactory = new WorkerCommandLineFactory();
    }

    /**
     * @param array<string, bool|string|int|null> $optionValues
     * @param string[] $paths
     */
    #[DataProvider('provideData')]
    public function test(array $optionValues, array $paths, string $expectedCommand): void
    {
        $workerCommandLine = $this->workerCommandLineFactory->create(
            self::DUMMY_MAIN_SCRIPT,
            'worker',
            null,
            $optionValues,
            $paths,
            'identifier',
            2000
        );

        $this->assertSame($expectedCommand, $workerCommandLine);
    }

    public static function provideData(): Iterator
    {
        $expectedCommandLinesString = self::createExpectedCommandLinesString();

        yield [[], ['src'], $expectedCommandLinesString];

        // output-format is excluded, so it must not change the result
        yield [[TestOption::OUTPUT_FORMAT => 'console'], ['src'], $expectedCommandLinesString];
    }

    private static function createExpectedCommandLinesString(): string
    {
        $commandLineString = "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "'";

        return $commandLineString . " worker --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi";
    }
}

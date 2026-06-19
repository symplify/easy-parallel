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
        yield 'no options, single path' => [[], ['src'], self::wrap('', "'src'")];

        yield 'output-format is excluded' => [
            [TestOption::OUTPUT_FORMAT => 'console'],
            ['src'],
            self::wrap('', "'src'"),
        ];

        yield 'true bool option becomes a flag' => [
            [TestOption::FIX => true],
            ['src'],
            self::wrap('--fix', "'src'"),
        ];

        yield 'false bool option is omitted' => [
            [TestOption::FIX => false],
            ['src'],
            self::wrap('', "'src'"),
        ];

        yield 'null option is omitted' => [
            [TestOption::MEMORY_LIMIT => null],
            ['src'],
            self::wrap('', "'src'"),
        ];

        yield 'memory-limit uses the assign form' => [
            [TestOption::MEMORY_LIMIT => '-1'],
            ['src'],
            self::wrap('--memory-limit=-1', "'src'"),
        ];

        yield 'string option keeps the escaped value' => [
            ['some-option' => 'value'],
            ['src'],
            self::wrap("--some-option 'value'", "'src'"),
        ];

        yield 'multiple paths are all escaped' => [[], ['src', 'tests'], self::wrap('', "'src' 'tests'")];

        yield 'framework global options are excluded' => [
            ['no-interaction' => true, 'verbose' => true, 'ansi' => true, 'help' => true],
            ['src'],
            self::wrap('', "'src'"),
        ];
    }

    public function testProjectConfigFileIsPassedRightAfterWorkerName(): void
    {
        $workerCommandLine = $this->workerCommandLineFactory->create(
            self::DUMMY_MAIN_SCRIPT,
            'worker',
            'ecs.php',
            [],
            ['src'],
            'identifier',
            2000
        );

        $expectedCommand = "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT
            . "' worker --config 'ecs.php' --port 2000 --identifier 'identifier' 'src' --output-format 'json' --no-ansi";

        $this->assertSame($expectedCommand, $workerCommandLine);
    }

    /**
     * Assemble the expected command line: options sit before --port, paths after --identifier.
     */
    private static function wrap(string $options, string $paths): string
    {
        $command = "'" . PHP_BINARY . "' '" . self::DUMMY_MAIN_SCRIPT . "' worker";

        if ($options !== '') {
            $command .= ' ' . $options;
        }

        return $command . " --port 2000 --identifier 'identifier' " . $paths . " --output-format 'json' --no-ansi";
    }
}

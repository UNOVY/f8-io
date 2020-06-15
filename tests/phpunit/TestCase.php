<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace Tests\PHPUnit;

use F8\IO\GuardDispatcher;
use F8\IO\Interfaces\ContextInterface;
use F8\IO\Interfaces\GuardInterface;
use F8\IO\Interfaces\InputInterface;
use F8\IO\Interfaces\OutputInterface;
use F8\IO\Interfaces\TaskHandlerInterface;
use F8\IO\Interfaces\TaskInterface;
use F8\IO\TaskHandler;
use PHPUnit\Framework\TestCase as PHPUnit;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

abstract class TestCase extends PHPUnit
{
    use ProphecyTrait;

    protected function createGuard(): GuardInterface
    {
        $g = $this->prophesize(GuardInterface::class);

        $g->process(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
            Argument::type(TaskHandlerInterface::class),
        )->willReturn(
            $this->prophesize(OutputInterface::class)->reveal()
        );

        return $g->reveal();
    }

    protected function createGuardDispatcher(?TaskHandlerInterface $taskHandler = null): GuardDispatcher
    {
        return new GuardDispatcher($taskHandler ?? $this->createTaskHandler());
    }

    protected function createTask(): TaskInterface
    {
        $t = $this->prophesize(TaskInterface::class);

        $t->do(
            Argument::type(ContextInterface::class),
            Argument::type(InputInterface::class),
        )->willReturn(
            $this->prophesize(OutputInterface::class)->reveal()
        );

        return $t->reveal();
    }

    protected function createTaskHandler(?TaskInterface $task = null): TaskHandler
    {
        return new TaskHandler($task ?? $this->createTask());
    }
}

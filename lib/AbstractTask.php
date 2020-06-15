<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO;

use F8\IO\Interfaces\ContextInterface;
use F8\IO\Interfaces\GuardDispatcherInterface;
use F8\IO\Interfaces\GuardInterface;
use F8\IO\Interfaces\InputInterface;
use F8\IO\Interfaces\OutputInterface;
use F8\IO\Interfaces\TaskInterface;
use RuntimeException;

/**
 * This is an example implementation of TaskInterface.
 * It supports autowiring of Guards using <<GuardInterface>> attributes,
 * as well as protection against guarding tasks that are not itself.
 * Note that the task is not instatiated by ::guarded(), but may be
 * instantiated by the implementation, thus allowing dependency injection.
 */
abstract class AbstractTask implements TaskInterface
{
    abstract public function do(
        ContextInterface $context,
        InputInterface $input
    ): OutputInterface;

    public static function guarded(TaskInterface $task): GuardDispatcherInterface
    {
        if (\get_class($task) !== static::class) {
            throw new RuntimeException(sprintf('Expected guarded task to be "%s"', static::class));
        }

        $taskHandler = new TaskHandler($task);

        $dispatcher = new GuardDispatcher($taskHandler);

        foreach (static::guards() as $guard) {
            $dispatcher->addGuard($guard);
        }

        return $dispatcher;
    }

    /**
     * @todo <<PhpAttribute>> with ReflectionClass::getAttibutes().
     *
     * @return GuardInterface[]
     */
    public static function guards(): array
    {
        return [];
    }
}

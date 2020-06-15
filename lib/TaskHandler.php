<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO;

use F8\IO\Interfaces\ContextInterface;
use F8\IO\Interfaces\InputInterface;
use F8\IO\Interfaces\OutputInterface;
use F8\IO\Interfaces\TaskHandlerInterface;
use F8\IO\Interfaces\TaskInterface;

final class TaskHandler implements TaskHandlerInterface
{
    private TaskInterface $task;

    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    public function handle(ContextInterface $context, InputInterface $input): OutputInterface
    {
        return $this->task->do($context, $input);
    }
}

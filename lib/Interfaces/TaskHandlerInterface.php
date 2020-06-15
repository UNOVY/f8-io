<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO\Interfaces;

/**
 * A TaskHandler wraps a Task with Guards which process Context & Input, Output
 * similiar to Request, Response in PSR-7/PSR-15.
 */
interface TaskHandlerInterface
{
    public function handle(
        ContextInterface $context,
        InputInterface $input
    ): OutputInterface;
}

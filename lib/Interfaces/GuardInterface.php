<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO\Interfaces;

/**
 * A Guard wraps a Task and processes Context & Input, Output similiar to
 * Middleware which processes Request, Response in PSR-7/PSR-15.
 */
// <<PhpAttribute>>
interface GuardInterface
{
    public function process(
        ContextInterface $context,
        InputInterface $input,
        TaskHandlerInterface $handler
    ): OutputInterface;
}

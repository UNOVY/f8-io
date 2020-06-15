<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO;

use F8\IO\Interfaces\ContextInterface;
use F8\IO\Interfaces\GuardInterface;
use F8\IO\Interfaces\InputInterface;
use F8\IO\Interfaces\OutputInterface;
use F8\IO\Interfaces\TaskHandlerInterface;

final class GuardHandler implements TaskHandlerInterface
{
    private GuardInterface $guard;

    private TaskHandlerInterface $next;

    public function __construct(GuardInterface $guard, TaskHandlerInterface $next)
    {
        $this->guard = $guard;
        $this->next = $next;
    }

    public function handle(ContextInterface $context, InputInterface $input): OutputInterface
    {
        return $this->guard->process($context, $input, $this->next);
    }
}

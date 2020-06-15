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
use F8\IO\Interfaces\TaskHandlerInterface;

final class GuardDispatcher implements GuardDispatcherInterface
{
    private TaskHandlerInterface $tip;

    public function __construct(TaskHandlerInterface $tip)
    {
        $this->seedGuardStack($tip);
    }

    public function addGuard(GuardInterface $guard): self
    {
        $next = $this->tip;

        $this->tip = new GuardHandler($guard, $next);

        return $this;
    }

    public function seedGuardStack(TaskHandlerInterface $tip): void
    {
        $this->tip = $tip;
    }

    public function handle(ContextInterface $context, InputInterface $input): OutputInterface
    {
        return $this->tip->handle($context, $input);
    }
}

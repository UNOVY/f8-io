<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO\Interfaces;

interface GuardDispatcherInterface extends TaskHandlerInterface
{
    public function addGuard(GuardInterface $guard): self;

    public function seedGuardStack(TaskHandlerInterface $guard): void;
}

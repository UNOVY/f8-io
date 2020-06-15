<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO\Interfaces;

/**
 * A Task SHOULD be one unit of business logic, or a composition of multiple Tasks.
 */
interface TaskInterface
{
    /**
     * Returns an instance of the task wrapped in its ::guards().
     */
    public static function guarded(self $task): GuardDispatcherInterface;

    /**
     * Returns this tasks guards.
     *
     * Tasks may statically define their guards, or could use a trait that
     * picks guards from its class <<GuardInterface>> attributes.
     *
     * @return GuardInterface[]
     */
    public static function guards(): array;

    /**
     * The task routine.
     *
     * This is public to allow a) easier unit-testing,
     * and b) performing calling tasks without guards.
     */
    public function do(
        ContextInterface $context,
        InputInterface $input
    ): OutputInterface;
}

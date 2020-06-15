<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO\Interfaces;

/**
 * Context SHOULD only contain trusted data.
 * Context SHOULD only contain data related to an individual invocation cycle.
 * Applications SHOULD define setter-methods that ensure trust in set values.
 * Applicatinos SHOULD define getter-methods that ensure type-safety by
 *  throwing instead of returning NULL.
 */
interface ContextInterface
{
}

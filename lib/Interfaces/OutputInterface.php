<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO\Interfaces;

use JsonSerializable;

/**
 * Output SHOULD contain (mixed) output data, serialization instructions,
 * and additional metadata (such as pagination or cursor, if applicable).
 */
interface OutputInterface extends JsonSerializable
{
    public function toArray(): array;
}

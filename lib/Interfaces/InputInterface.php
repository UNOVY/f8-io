<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO\Interfaces;

/**
 * Input SHOULD NOT be trusted.
 * Applications SHOULD use Guards to take values from Input and validate them.
 */
interface InputInterface
{
    /**
     * Whether the input has a key set.
     */
    public function has(string $key): bool;

    /**
     * Get an input value by its key.
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * Get an input value by its key and unset it.
     *
     * @return mixed
     */
    public function take(string $key);

    /**
     * Whether the input has any keys left.
     */
    public function empty(): bool;
}

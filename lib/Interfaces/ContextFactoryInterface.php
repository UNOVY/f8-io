<?php

/**
 * @author Florian Gärber <florian@unovy.io>
 * @copyright 2020 Florian Gärber
 * @license MIT
 */

declare(strict_types=1);

namespace F8\IO\Interfaces;

interface ContextFactoryInterface
{
    /**
     * SHOULD return a new, empty Context.
     */
    public function createContext(): ContextInterface;
}

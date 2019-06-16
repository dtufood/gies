<?php

namespace DTU\Food\GIES\Contracts;

interface Ingredients
{
    /**
     * Creates new ingredients instance.
     *
     * @param string $tree
     * @param mixed  $index
     * @param mixed  $weight
     * @param string $assembly
     */
    public function __construct($tree, $index, $weight, $assembly);

    /**
     * Get the total sum.
     *
     * @return mixed
     */
    public function sum();

    /**
     * Returns all keys.
     *
     * @return array
     */
    public function keys();
}

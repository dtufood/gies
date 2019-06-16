<?php

namespace DTU\Food\GIES;

use DTU\Food\GIES\Traits\TreeFlatMapperTrait;
use ArrayObject;

class Ingredients extends ArrayObject implements Contracts\Ingredients
{
    use TreeFlatMapperTrait;

    /**
     * Creates new ingredients instance.
     *
     * @param string $tree
     * @param mixed  $index
     * @param mixed  $weight
     * @param string $assembly
     */
    public function __construct($tree, $index, $weight, $assembly)
    {
        parent::__construct(
            $this->flattern(
                $tree,
                $weight,
                $assembly,
                function ($item) use ($index) {
                    // Make the new indexes for the tree nodes
                    return $this->makeIndex(
                        is_array($index) ? $index : [$index],
                        $item
                    );
                }
            )
        );
    }

    /**
     * Get the total sum.
     *
     * @return mixed
     */
    public function sum()
    {
        return array_sum((array) $this);
    }

    /**
     * Returns all keys.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys((array) $this);
    }

    /**
     * Make an index from the specified attributes.
     *
     * @param array $index
     * @param mixed $item
     *
     * @return string
     */
    protected function makeIndex(array $index, $item)
    {
        $values = [];

        foreach ($index as $key) {
            $values[] = $item[$key];
        }

        return implode('-', $values);
    }
}

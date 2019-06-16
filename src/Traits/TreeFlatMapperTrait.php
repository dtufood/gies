<?php

namespace DTU\Food\GIES\Traits;

trait TreeFlatMapperTrait
{
    /**
     * Resolves the tree to a list of base item quantities.
     *
     * @param array  $trees
     * @param mixed  $attribute
     * @param string $node
     * @param array  $index
     *
     * @return array
     */
    protected function flattern($trees, $attribute, $node, $index)
    {
        $quantities = [];

        // Loop through the trees and flattern them
        foreach ($trees as $item) {
            // If the item is an node we must recursivle
            // flattern that node until the leaf is reached
            if (!empty($item[$node])) {
                // Resolve the node and scale the sub node weights relative to the parent
                $quantities = $this->scale(
                    $item[$attribute],
                    $this->flattern(
                        $item[$node],
                        $attribute,
                        $node,
                        $index
                    ),
                    $quantities
                );
            } else {
                // Get the key to index the item by
                $key = is_callable($index) ? call_user_func($index, $item) : $index;

                // Sum the total quantity of base items
                $quantities = $this->add($key, $item[$attribute], $quantities);
            }
        }

        return $quantities;
    }

    /**
     * This scales the relative weights of each item.
     *
     * @param mixed $weight
     * @param array $items
     * @param array $quantities
     *
     * @return array
     */
    protected function scale($weight, $items, $quantities)
    {
        // Total weight of all ingredients
        $total_weight = array_sum($items);

        foreach ($items as $key => $quantity) {
            if (!array_key_exists($key, $quantities)) {
                $quantities[$key] = 0;
            }
            // Total weight of particular ingredient is the sub recipes ingredient
            // out of the sub recipes full weight times the quantity for this particular item
            $quantities[$key] += $weight * ($quantity / $total_weight);
        }

        return $quantities;
    }

    /**
     * This adds the weight to the total weight of each base item.
     *
     * @param mixed $key
     * @param mixed $weight
     * @param array $quantities
     *
     * @return array
     */
    protected function add($key, $weight, $quantities)
    {
        if (!array_key_exists($key, $quantities)) {
            $quantities[$key] = 0;
        }

        $quantities[$key] += $weight;

        return $quantities;
    }
}

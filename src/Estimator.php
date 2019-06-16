<?php

namespace DTU\Food\GIES;

use DTU\Food\GIES\Contracts\Ingredients;

class Estimator
{
    /**
     * The portion.
     *
     * @var \DTU\Food\GIES\Portion
     */
    public $portion;

    /**
     * Creates a new estimator instance.
     *
     * @param \DTU\Food\GIES\Portion $portion
     */
    public function __construct(Portion $portion)
    {
        $this->portion = $portion;
    }

    /**
     * Estimate the ingredients composition.
     *
     * @param \DTU\Food\GIES\Contracts\Ingredients $ingredients
     * @param array|\Closure                       $parameters
     *
     * @return \DTU\Food\GIES\Estimator
     */
    public function estimate(Ingredients $ingredients, $composition)
    {
        foreach ($ingredients as $key => $quantity) {
            $this->portion->add(
                // Composition parameters can be fetched on-demand when needed
                // by passing a callback function rather than passing a large
                // array of composition parameter sets.
                is_callable($composition) ?
                    call_user_func($composition, $key)->setScale($quantity) :
                    $composition[$key]->setScale($quantity),
                $ingredients->sum()
            );
        }

        return $this;
    }

    /**
     * Scales the composition parameters to given portion size.
     *
     * @param mixed $size
     *
     * @return \DTU\Food\GIES\Estimator
     */
    public function portion($size)
    {
        $this->portion->setSize($size);

        return $this;
    }
}

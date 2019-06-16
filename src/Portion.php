<?php

namespace DTU\Food\GIES;

use DTU\Food\GIES\Contracts\Parameters;
use ArrayObject;

class Portion extends ArrayObject
{
    /**
     * The portion size.
     *
     * @var mixed
     */
    protected $size;

    /**
     * Creates a portion instance.
     *
     * @param mixed                               $size
     * @param \DTU\Food\GIES\Contracts\Parameters $parameters
     * @param mixed                               $weight
     */
    public function __construct($size = 0.1)
    {
        // Set the portion size property
        $this->size = $size;
    }

    /**
     * Sets the portion size.
     *
     * @param mixed $size
     *
     * @return \DTU\Food\GIES\Portion
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Gets the portion size.
     *
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Adds parameters to the portion.
     *
     * @param \DTU\Food\GIES\Contracts\Parameters $parameters
     * @param mixed                               $quantity
     *
     * @return \DTU\Food\GIES\Portion;
     */
    public function add(Parameters $parameters, $quantity = 0.1)
    {
        // Sum new parameters values with existing values
        foreach ($parameters->all() as $parameter => $value) {
            $this->set($parameter, $value, $quantity);
        }

        return $this;
    }

    /**
     * Sets composition parameter scaled by ingredient quantity and portion size.
     *
     * @param mixed $parameter
     * @param mixed $value
     * @param mixed $quantity
     */
    public function set($parameter, $value, $quantity = 0.1)
    {
        // Set the array offset
        $this->offsetSet(
            $parameter,
            // If the offset already exists add the value to the existing
            $this->offsetExists($parameter) ?
                $this->scale($value, $quantity) + $this->offsetGet($parameter) :
                $this->scale($value, $quantity)
        );
    }

    /**
     * Scales the parameter value by ingredient quantity and portion size.
     *
     * @param mixed $value
     * @param mixed $quantity
     *
     * @return mixed
     */
    protected function scale($value, $quantity)
    {
        return $value / $quantity * $this->size;
    }
}

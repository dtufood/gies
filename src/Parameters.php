<?php

namespace DTU\Food\GIES;

use ArrayObject;

class Parameters extends ArrayObject implements Contracts\Parameters
{
    /**
     * The scale property.
     *
     * @var mixed
     */
    protected $scale;

    /**
     * Array of parameters.
     *
     * @var array
     */
    protected $container = [];

    /**
     * Creates a new parameters instance.
     *
     * @param array $parameters
     * @param array $retentions
     * @param mixed $scale
     */
    public function __construct(array $parameters, array $retentions = [], $scale = 1.0)
    {
        // Set the scale property
        $this->scale = $scale;

        // Factor any retention values there might be
        foreach ($parameters as $parameter => $quantity) {
            // Get the retention factor if any
            $factor = array_key_exists($parameter, $retentions) ? $retentions[$parameter] : 1;

            $this->container[$parameter] = $quantity * $factor;
        }
    }

    /**
     * Sets the scale value.
     *
     * @param mixed $scale
     *
     * @return \DTU\Food\GIES\Parameters
     */
    public function setScale($scale)
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * Gets the scale.
     *
     * @return mixed
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Get all parameters.
     *
     * @return array
     */
    public function all()
    {
        return array_map(
            function ($value) {
                return $this->scale($value);
            },
            $this->container
        );
    }

    /**
     * Gets a parameter.
     *
     * @param mixed $parameter
     *
     * @return mixed
     */
    public function get($parameter)
    {
        return $this->scale($this->container[$parameter]);
    }

    /**
     * Offset getter.
     *
     * @param mixed $index
     *
     * @return mixed
     */
    protected function scale($value)
    {
        return $value * $this->scale;
    }
}

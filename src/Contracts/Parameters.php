<?php

namespace DTU\Food\GIES\Contracts;

interface Parameters
{
    /**
     * Creates a new parameters instance.
     *
     * @param array $parameters
     * @param array $retentions
     * @param float $scale
     */
    public function __construct(array $parameters, array $retentions = [], $scale = 1.0);

    /**
     * Sets the scale value.
     *
     * @param mixed $scale
     *
     * @return \DTU\Food\GIES\Parameters
     */
    public function setScale($scale);

    /**
     * Gets the scale.
     *
     * @return mixed
     */
    public function getScale();

    /**
     * Get all parameters.
     *
     * @return array
     */
    public function all();

    /**
     * Get a parameter.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function get($key);
}

<?php

namespace DTU\Food\GIES\Tests;

use DTU\Food\GIES\Parameters;
use PHPUnit\Framework\TestCase;

class ParametersTest extends TestCase
{
    /**
     * This test checks if the Parameters class can be instantiated from valid data.
     *
     * @dataProvider parametersAndRetentionsProvider
     */
    public function testCanCreateFromValidData(array $parameters, array $retentions, $scale, array $expected)
    {
        $instance = $scale === null ?
            new Parameters($parameters, $retentions) :
            new Parameters($parameters, $retentions, $scale);

        $this->assertEquals($expected, $instance->all());
        $this->assertEquals($scale !== null ? $scale : 1.0, $instance->getScale());
    }

    /**
     * This test checks if scale factor can be set.
     *
     * @dataProvider parametersAndRetentionsProvider
     */
    public function testScaleCanBeSet(array $parameters, array $retentions, $scale, array $expected)
    {
        $instance = new Parameters($parameters, $retentions);

        // Verify that the original scale value is 1
        $this->assertEquals(1, $instance->getScale());

        // Change the scale
        $instance->setScale($scale !== null ? $scale : 1.0);

        // Verify the parameters has been scaled accordingly
        $this->assertEquals($scale !== null ? $scale : 1.0, $instance->getScale());
        $this->assertEquals($expected, $instance->all());
    }

    /**
     * This provides the test sample parameters.
     *
     * @return array
     */
    public function parametersAndRetentionsProvider()
    {
        return [
            // Test Sample 1
            [
                // Parameters
                [
                    'a' => 0.008,
                    'b' => 30.5,
                    'c' => 113,
                    'd' => 4.62,
                    'e' => 0,
                ],
                // Retentions
                [
                    'c' => 0.50,
                    'd' => 1,
                ],
                // Scale
                null,
                // Expected result
                [
                    'a' => 0.008,
                    'b' => 30.5,
                    'c' => 56.5,
                    'd' => 4.62,
                    'e' => 0.0,
                ],
            ],
            // Test sample 2
            [
                // Parameters
                [
                    'a' => 0.584,
                    'b' => 41.41,
                    'c' => 1134,
                    'd' => 0,
                    'e' => 5,
                ],
                // Retentions
                [
                    'c' => 0.9,
                    'd' => 1,
                    'e' => 0.11,
                ],
                // Scale
                2,
                // Expected result
                [
                    'a' => 1.168,
                    'b' => 82.82,
                    'c' => 2041.2,
                    'd' => 0,
                    'e' => 1.1,
                ],
            ],
        ];
    }
}

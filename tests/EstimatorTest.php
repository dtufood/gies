<?php

namespace DTU\Food\GIES\Tests;

use DTU\Food\GIES\Estimator;
use DTU\Food\GIES\Ingredients;
use DTU\Food\GIES\Parameters;
use DTU\Food\GIES\Portion;
use DTU\Food\GIES\Contracts\Parameters as ParametersContract;
use PHPUnit\Framework\TestCase;

class EstimatorTest extends TestCase
{
    /**
     * This test checks if composition parameters can be estimated.
     */
    public function testCanEstimateSandwichComposition()
    {
        // A ham sandwich recipe tree
        $sandwich = [
            [
                // Slice 50 grams of ham
                'name' => 'Slice of ham',
                'quantity' => 0.050,
                'ingredients' => [
                    [
                        // From a smoked ham of 1 kilogram
                        'name' => 'Smoked ham',
                        'quantity' => 1,
                    ],
                ],
            ],
            [
                // Cut a slice of bread weighing 25 grams
                'name' => 'Slice of bread',
                'quantity' => 0.025,
                'ingredients' => [
                    // From 400 grams bread loaf
                    [
                        'name' => 'Bread loaf',
                        'quantity' => 0.4,
                    ],
                ],
            ],
            // Add 5 grams of butter
            [
                'name' => 'Butter',
                'quantity' => 0.005,
            ],
        ];

        // Composition parameters of base ingredients
        $composition = [
            'Smoked ham' => new Parameters(
                [
                    'Energy, kJ' => 467,
                    'Fat, total' => 14.0,
                ]
            ),
            'Bread loaf' => new Parameters(
                [
                    'Energy, kJ' => 942,
                    'Fat, total' => 1.7,
                ]
            ),
            'Butter' => new Parameters(
                [
                    'Energy, kJ' => 3047,
                    'Fat, total' => 81.5,
                ]
            ),
        ];

        // Make a new estimator
        $estimator = new Estimator(new Portion());

        // Get base ingredients quantities
        $ingredients = new Ingredients($sandwich, 'name', 'quantity', 'ingredients');

        // Estimate recipe composition parameters from composition data
        $estimator->estimate($ingredients, $composition);

        // Check if the recipe tree was correctly resolved
        $this->assertEquals(
            [
                'Smoked ham' => 0.05,
                'Bread loaf' => 0.025,
                'Butter' => 0.005,
            ],
            (array) $ingredients
        );

        // Check if the composition parameters was correctly calculated
        $this->assertEquals(
            [
                'Energy, kJ' => 77.66874999999999,
                'Fat, total' => 1.4375,
            ],
            (array) $estimator->portion
        );
    }

    /**
     * This test checks if an estimator instance can be created.
     *
     * @return \DTU\Food\GIES\Estimator
     */
    public function testCanBeCreated()
    {
        $estimator = new Estimator(new Portion());

        $this->assertInstanceOf(Estimator::class, $estimator);

        return $estimator;
    }

    /**
     * This test checks if a parameter class instance can be passed.
     *
     * @depends testCanBeCreated
     *
     * @param \DTU\Food\GIES\Estimator $estimator
     *
     * @return \DTU\Food\GIES\Estimator
     */
    public function testCanAddParameterClass(Estimator $estimator)
    {
        $ingredients = new Ingredients(
            [
                [
                    'id' => 1,
                    'value' => 100,
                    'ingredients' => [
                        [
                            'id' => 2,
                            'value' => 150,
                        ],
                        [
                            'id' => 3,
                            'value' => 50,
                        ],
                    ],
                ],
            ],
            ['id'],
            'value',
            'ingredients'
        );

        $composition = [
            2 => new Parameters(
                [
                    'a' => 0.8,
                    'b' => 30.5,
                    'c' => 56.5,
                    'd' => 4.62,
                    'e' => 0.0,
                ],
                []
            ),
            3 => new Parameters(
                [
                    'a' => 54,
                    'b' => 0.021,
                    'c' => 2.11,
                    'd' => 4.62,
                    'e' => 0.0,
                ],
                []
            ),
        ];

        $estimator->portion(1)->estimate($ingredients, $composition);

        $this->assertEquals(
            [
                'a' => 14.1,
                'b' => 22.88025,
                'c' => 42.9025,
                'd' => 4.62,
                'e' => 0.0,
            ],
            (array) $estimator->portion
        );

        return $estimator;
    }

    /**
     * This test checks if parameters can be passed via callback.
     *
     * @depends testCanAddParameterClass
     *
     * @param \DTU\Food\GIES\Estimator $estimator
     */
    public function testCanAddParameterClassViaClosure(Estimator $estimator)
    {
        $ingredients = new Ingredients(
            [
                [
                    'id' => 1,
                    'value' => 10.0,
                    'ingredients' => [
                        [
                            'id' => 2,
                            'value' => 5,
                        ],
                        [
                            'id' => 3,
                            'value' => 2.5,
                        ],
                    ],
                ],
            ],
            ['id'],
            'value',
            'ingredients'
        );

        $composition = [
            2 => new Parameters(
                [
                    'a' => 1,
                    'b' => 2.4,
                    'c' => 4471,
                    'd' => 5.89,
                    'e' => 424.2,
                ]
            ),
            3 => new Parameters(
                [
                    'a' => 1,
                    'b' => 2.4,
                    'c' => 4471,
                    'd' => 5.89,
                    'e' => 424.2,
                ]
            ),
        ];

        $estimator->estimate(
            $ingredients,
            function ($id) use ($composition) {
                return $composition[$id];
            }
        );

        $this->assertEquals(
            [
                'a' => 15.1,
                'b' => 25.280250000000002,
                'c' => 4513.9025,
                'd' => 10.51,
                'e' => 424.19999999999993,
            ],
            (array) $estimator->portion
        );
    }

    /**
     * @depends testCanBeCreated
     *
     * @param \DTU\Food\GIES\Estimator $estimator
     */
    public function testParametersGetsScaledByQuantity(Estimator $estimator)
    {
        // Create a new ingredients instance
        $ingredients = new Ingredients(
            [
                [
                    'id' => 1,
                    'value' => 100,
                ],
            ],
            ['id'],
            'value',
            'ingredients'
        );

        // Create the mocks
        $stub = $this->createMock(ParametersContract::class);

        // Configure the expectations for the stub
        $stub->expects($this->once())
            ->method('setScale')
            ->with(100)
            ->willReturnSelf();

        $stub->expects($this->once())
            ->method('all')
            ->willReturn([]);

        // Test expectations
        $estimator->portion(1)->estimate($ingredients, [1 => $stub]);
    }
}

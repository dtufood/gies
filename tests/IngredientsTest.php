<?php

namespace DTU\Food\GIES\Tests;

use DTU\Food\GIES\Ingredients;
use PHPUnit\Framework\TestCase;

class IngredientsTest extends TestCase
{
    /**
     * This test will check if the recipe tree can be mapped to a list of base ingredients.
     *
     * @dataProvider recipeIngredientTreeProvider
     */
    public function testCanFlatternRecipe($tree, $expected)
    {
        $ingredients = new Ingredients(
            $tree,
            ['id', 'preperation_id'],
            'value',
            'ingredients'
        );

        $this->assertEquals($expected, (array) $ingredients);
        $this->assertEquals(array_sum($expected), $ingredients->sum());

        return $ingredients;
    }

    /**
     * This test checks if composite indexes can be created.
     *
     * @dataProvider recipeIngredientTreeProvider
     *
     * @param \DTU\Food\GIES\Ingredients $ingredients
     */
    public function testCanMakeCompositeIndex($tree, $expected)
    {
        $ingredients = new Ingredients(
            $tree,
            ['id', 'preperation_id'],
            'value',
            'ingredients'
        );

        foreach ($ingredients->keys() as $composite) {
            $this->assertCount(2, explode('-', $composite));
            $this->assertArrayHasKey($composite, $expected);
        }
    }

    /**
     * This provides the test sample recipes trees.
     *
     * @return array
     */
    public function recipeIngredientTreeProvider()
    {
        return [
            // Test Recipe 1 (simple)
            [
                // Ingredients
                [
                    [
                        'id' => 54,
                        'preperation_id' => 1,
                        'value' => 240.0,
                    ],
                    [
                        'id' => 71,
                        'preperation_id' => 1,
                        'value' => 3.0,
                    ],
                    [
                        'id' => 46,
                        'preperation_id' => 1,
                        'value' => 10.0,
                    ],
                ],
                // Expected result
                [
                    '54-1' => 240.0,
                    '71-1' => 3.0,
                    '46-1' => 10.0,
                ],
            ],
            // Test Recipe 2 (complex)
            [
                // Ingredients
                [
                    [
                        'id' => 80,
                        'preperation_id' => 1,
                        'value' => 0.01,
                        'ingredients' => [
                            [
                                'id' => 81,
                                'preperation_id' => 4,
                                'value' => 0.015,
                            ],
                            [
                                'id' => 84,
                                'preperation_id' => 4,
                                'value' => 0.005,
                            ],
                        ],
                    ],
                    [
                        'id' => 82,
                        'preperation_id' => 3,
                        'value' => 0.5,
                        'ingredients' => [
                            [
                                'id' => 83,
                                'preperation_id' => 2,
                                'value' => 0.1,
                            ],
                        ],
                    ],
                    [
                        'id' => 84,
                        'preperation_id' => 4,
                        'value' => 0.001,
                    ],
                ],
                // Expected result
                [
                    '81-4' => 0.0075,
                    '84-4' => 0.0035,
                    '83-2' => 0.5,
                ],
            ],
        ];
    }
}

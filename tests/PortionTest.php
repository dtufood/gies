<?php

namespace DTU\Food\GIES\Tests;

use PHPUnit\Framework\TestCase;
use DTU\Food\GIES\Portion;
use DTU\Food\GIES\Contracts\Parameters;

class PortionTest extends TestCase
{
    /**
     * Tests if the portion class can be instantiated from valid input.
     *
     * @dataProvider canBeCreatedFromValidDataProvider
     */
    public function testCanBeCreatedFromValidData($size, $expected)
    {
        $portion = $size ? new Portion($size) : new Portion();

        $this->assertInstanceOf(Portion::class, $portion);
        $this->assertEquals($expected, (array) $portion);
    }

    /**
     * Provides data for the testCanBeCreatedFromValidData test.
     *
     * @return array
     */
    public function canBeCreatedFromValidDataProvider()
    {
        return [
            // Test case 1
            [
                null,
                [],
            ],
            // Test case 2
            [
                2,
                [],
            ],
        ];
    }

    /**
     * This test checks if the Portion class can be instantiated.
     *
     * @return \DTU\Food\GIES\Portion
     */
    public function testCanBeCreatedWithNoParameters()
    {
        $portion = new Portion();

        $this->assertInstanceOf(Portion::class, $portion);

        return $portion;
    }

    /**
     * This test checks if parameters gets summed.
     *
     * @param \DTU\Food\GIES\Portion $portion
     *
     * @depends testCanBeCreatedWithNoParameters
     */
    public function testAllParametersGetsSummed(Portion $portion)
    {
        // Create the mocks
        $stub1 = $this->createMock(Parameters::class);
        $stub2 = $this->createMock(Parameters::class);

        // Configure the expectations for stub 1
        $stub1->expects($this->once())
            ->method('all')
            ->willReturn(['a' => 15.2,  'b' => 0.1]);

        $stub2->expects($this->once())
            ->method('all')
            ->willReturn(['a' => 388,  'b' => 0]);

        $portion->add($stub1);
        $portion->add($stub2);

        $this->assertEquals(['a' => 403.2, 'b' => 0.1], (array) $portion);
    }
}

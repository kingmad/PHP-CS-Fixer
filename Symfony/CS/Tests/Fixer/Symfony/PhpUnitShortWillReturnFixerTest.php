<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Tests\Fixer\Symfony;

use Symfony\CS\Tests\Fixer\AbstractFixerTestBase;

/**
 * PHPUnit Short Will Return Fixer Test.
 */
class PhpUnitShortWillReturnFixerTest extends AbstractFixerTestBase
{
    /**
     * @param string        $expected
     * @param string | null $input
     *
     * @dataProvider provideFixCases
     */
    public function testFix($expected, $input = null)
    {
        $this->makeTest($expected, $input);
    }

    /**
     * @return array
     */
    public function provideFixCases()
    {
        return array(
            array(
                '<?php
                $testMock = $this->getTestMock();
        $testMock->method(\'testMethod\')
            ->willReturn($returnValue);
',
                '<?php
                $testMock = $this->getTestMock();
        $testMock->method(\'testMethod\')
            ->will($this->returnValue($returnValue));
',
            ),
            array(
                '<?php

                $finderMock->expects($this->at(0))
            ->method(\'getUnits\')
            ->with(\'fileName1\')
            ->willReturn(\'Unit1\');
        $finderMock->expects($this->at(1))
            ->method(\'getUnits\')
            ->with(\'fileName2\')
            ->willReturn(\'Unit2\');
        $finderMock->expects($this->at(2))
            ->method(\'getUnits\')
            ->with(\'fileName3\')
            ->willReturn(\'Unit3\');
                ',
                '<?php

                $finderMock->expects($this->at(0))
            ->method(\'getUnits\')
            ->with(\'fileName1\')
            ->will($this->returnValue(\'Unit1\'));
        $finderMock->expects($this->at(1))
            ->method(\'getUnits\')
            ->with(\'fileName2\')
            ->will($this->returnValue(\'Unit2\'));
        $finderMock->expects($this->at(2))
            ->method(\'getUnits\')
            ->with(\'fileName3\')
            ->will($this->returnValue(\'Unit3\'));
                ',
            ),
            array(
                '<?php
                $testMock->method("test_method")->willReturn(self::DEFAULT_VALUE);',
                '<?php
                $testMock->method("test_method")->will($this->returnValue(self::DEFAULT_VALUE));',
            ),
        );
    }
}

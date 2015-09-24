<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers\Format;

/*                                                                        *
 * This script belongs to the Flow framework.                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the MIT license.                                          *
 *                                                                        */

require_once(__DIR__ . '/../ViewHelperBaseTestcase.php');

use TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase;

/**
 * Test for TYPO3\Fluid\ViewHelpers\Format\PaddingViewHelper
 */
class PaddingViewHelperTest extends ViewHelperBaseTestcase
{
    /**
     * @var \TYPO3\Fluid\ViewHelpers\Format\PaddingViewHelper
     */
    protected $viewHelper;

    public function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->getMock(\TYPO3\Fluid\ViewHelpers\Format\PaddingViewHelper::class, array('renderChildren'));
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
    }

    /**
     * @test
     */
    public function stringsArePaddedWithBlanksByDefault()
    {
        $this->viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('foo'));
        $actualResult = $this->viewHelper->render(10);
        $this->assertEquals('foo       ', $actualResult);
    }

    /**
     * @test
     */
    public function paddingStringCanBeSpecified()
    {
        $this->viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('foo'));
        $actualResult = $this->viewHelper->render(10, '-=');
        $this->assertEquals('foo-=-=-=-', $actualResult);
    }

    /**
     * @test
     */
    public function stringIsNotTruncatedIfPadLengthIsBelowStringLength()
    {
        $this->viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('some long string'));
        $actualResult = $this->viewHelper->render(5);
        $this->assertEquals('some long string', $actualResult);
    }

    /**
     * @test
     */
    public function integersArePaddedCorrectly()
    {
        $this->viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(123));
        $actualResult = $this->viewHelper->render(5, '0');
        $this->assertEquals('12300', $actualResult);
    }
}

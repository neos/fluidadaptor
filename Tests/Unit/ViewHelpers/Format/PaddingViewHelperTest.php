<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers\Format;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Test for TYPO3\Fluid\ViewHelpers\Format\PaddingViewHelper
 */
class PaddingViewHelperTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function stringsArePaddedWithBlanksByDefault() {
		$viewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Format\PaddingViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('foo'));
		$actualResult = $viewHelper->render(10);
		$this->assertEquals('foo       ', $actualResult);
	}

	/**
	 * @test
	 */
	public function paddingStringCanBeSpecified() {
		$viewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Format\PaddingViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('foo'));
		$actualResult = $viewHelper->render(10, '-=');
		$this->assertEquals('foo-=-=-=-', $actualResult);
	}

	/**
	 * @test
	 */
	public function stringIsNotTruncatedIfPadLengthIsBelowStringLength() {
		$viewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Format\PaddingViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('some long string'));
		$actualResult = $viewHelper->render(5);
		$this->assertEquals('some long string', $actualResult);
	}

	/**
	 * @test
	 */
	public function integersArePaddedCorrectly() {
		$viewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Format\PaddingViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(123));
		$actualResult = $viewHelper->render(5, '0');
		$this->assertEquals('12300', $actualResult);
	}
}

?>
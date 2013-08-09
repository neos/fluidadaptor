<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers\Format;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 */
class NumberViewHelperTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function formatNumberDefaultsToEnglishNotationWithTwoDecimals() {
		$viewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Format\NumberViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(10000.0 / 3.0));
		$actualResult = $viewHelper->render();
		$this->assertEquals('3,333.33', $actualResult);
	}

	/**
	 * @test
	 */
	public function formatNumberWithDecimalsDecimalPointAndSeparator() {
		$viewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Format\NumberViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(10000.0 / 3.0));
		$actualResult = $viewHelper->render(3, ',', '.');
		$this->assertEquals('3.333,333', $actualResult);
	}
}
?>
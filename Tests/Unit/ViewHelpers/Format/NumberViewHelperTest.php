<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Tests\Unit\ViewHelpers\Format;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License as published by the Free   *
 * Software Foundation, either version 3 of the License, or (at your      *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        *
 * You should have received a copy of the GNU General Public License      *
 * along with the script.                                                 *
 * If not, see http://www.gnu.org/licenses/gpl.html                       *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 */
class NumberViewHelperTest extends \F3\Testing\BaseTestCase {

	/**
	 * @test
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function formatNumberDefaultsToEnglishNotationWithTwoDecimals() {
		$viewHelper = $this->getMock('F3\Fluid\ViewHelpers\Format\NumberViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(10000.0 / 3.0));
		$actualResult = $viewHelper->render();
		$this->assertEquals('3,333.33', $actualResult);
	}

	/**
	 * @test
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function formatNumberWithDecimalsDecimalPointAndSeparator() {
		$viewHelper = $this->getMock('F3\Fluid\ViewHelpers\Format\NumberViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(10000.0 / 3.0));
		$actualResult = $viewHelper->render(3, ',', '.');
		$this->assertEquals('3.333,333', $actualResult);
	}
}
?>
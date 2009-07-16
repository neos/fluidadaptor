<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\ViewHelpers\Format;

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
 * @version $Id$
 */
class CurrencyViewHelperTest extends \F3\Testing\BaseTestCase {

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperRoundsFloatCorrectly() {
		$viewHelper = $this->getMock('F3\Fluid\ViewHelpers\Format\CurrencyViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(123.456));
		$actualResult = $viewHelper->render();
		$this->assertEquals('123,46', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperRendersCurrencySign() {
		$viewHelper = $this->getMock('F3\Fluid\ViewHelpers\Format\CurrencyViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(123));
		$actualResult = $viewHelper->render('foo');
		$this->assertEquals('123,00 foo', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperRespectsDecimalSeparator() {
		$viewHelper = $this->getMock('F3\Fluid\ViewHelpers\Format\CurrencyViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(12345));
		$actualResult = $viewHelper->render('', '|');
		$this->assertEquals('12.345|00', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperRespectsThousandsSeparator() {
		$viewHelper = $this->getMock('F3\Fluid\ViewHelpers\Format\CurrencyViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(12345));
		$actualResult = $viewHelper->render('', ',', '|');
		$this->assertEquals('12|345,00', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperRendersNullValues() {
		$viewHelper = $this->getMock('F3\Fluid\ViewHelpers\Format\CurrencyViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(NULL));
		$actualResult = $viewHelper->render();
		$this->assertEquals('0,00', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperRendersNegativeAmounts() {
		$viewHelper = $this->getMock('F3\Fluid\ViewHelpers\Format\CurrencyViewHelper', array('renderChildren'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue(-123.456));
		$actualResult = $viewHelper->render();
		$this->assertEquals('-123,46', $actualResult);
	}
}
?>

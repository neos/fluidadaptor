<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/ViewHelperBaseTestcase.php');

/**
 * Testcase for RenderViewHelper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class RenderViewHelperTest extends \TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * var \TYPO3\Fluid\ViewHelpers\RenderViewHelper
	 */
	protected $viewHelper;

	/**
	 * var \TYPO3\Fluid\Core\ViewHelper\Arguments
	 */
	protected $mockArguments;

	public function setUp() {
		parent::setUp();
		$this->templateVariableContainer = new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
		$this->viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\RenderViewHelper', array('dummy'));
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function loadSettingsIntoArgumentsSetsSettingsIfNoSettingsAreSpecified() {
		$arguments = array(
			'someArgument' => 'someValue'
		);
		$expected = array(
			'someArgument' => 'someValue',
			'settings' => 'theSettings'
		);
		$this->templateVariableContainer->add('settings', 'theSettings');

		$actual = $this->viewHelper->_call('loadSettingsIntoArguments', $arguments);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function loadSettingsIntoArgumentsDoesNotOverrideGivenSettings() {
		$arguments = array(
			'someArgument' => 'someValue',
			'settings' => 'specifiedSettings'
		);
		$expected = array(
			'someArgument' => 'someValue',
			'settings' => 'specifiedSettings'
		);
		$this->templateVariableContainer->add('settings', 'theSettings');

		$actual = $this->viewHelper->_call('loadSettingsIntoArguments', $arguments);
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function loadSettingsIntoArgumentsDoesNotThrowExceptionIfSettingsAreNotInTemplateVariableContainer() {
		$arguments = array(
			'someArgument' => 'someValue'
		);
		$expected = array(
			'someArgument' => 'someValue'
		);

		$actual = $this->viewHelper->_call('loadSettingsIntoArguments', $arguments);
		$this->assertEquals($expected, $actual);
	}


}

?>
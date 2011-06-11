<?php
namespace F3\Fluid\Tests\Unit\ViewHelpers;

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
 * Testcase for CycleViewHelper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class CycleViewHelperTest extends \F3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * var \F3\Fluid\ViewHelpers\CycleViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = $this->getMock('F3\Fluid\ViewHelpers\CycleViewHelper', array('renderChildren'));
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsCurrentValueToTemplateVariableContainerAndRemovesItAfterRendering() {
		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('innerVariable', 'bar');
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('innerVariable');

		$values = array('bar', 'Fluid');
		$this->viewHelper->render($values, 'innerVariable');
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsFirstValueToTemplateVariableContainerAfterLastValue() {
		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('innerVariable', 'bar');
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('innerVariable');
		$this->templateVariableContainer->expects($this->at(2))->method('add')->with('innerVariable', 'Fluid');
		$this->templateVariableContainer->expects($this->at(3))->method('remove')->with('innerVariable');
		$this->templateVariableContainer->expects($this->at(4))->method('add')->with('innerVariable', 'bar');
		$this->templateVariableContainer->expects($this->at(5))->method('remove')->with('innerVariable');

		$values = array('bar', 'Fluid');
		$this->viewHelper->render($values, 'innerVariable');
		$this->viewHelper->render($values, 'innerVariable');
		$this->viewHelper->render($values, 'innerVariable');
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperSupportsAssociativeArrays() {
		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('innerVariable', 'FLOW3');
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('innerVariable');
		$this->templateVariableContainer->expects($this->at(2))->method('add')->with('innerVariable', 'Fluid');
		$this->templateVariableContainer->expects($this->at(3))->method('remove')->with('innerVariable');
		$this->templateVariableContainer->expects($this->at(4))->method('add')->with('innerVariable', 'FLOW3');
		$this->templateVariableContainer->expects($this->at(5))->method('remove')->with('innerVariable');

		$values = array('foo' => 'FLOW3', 'bar' => 'Fluid');
		$this->viewHelper->render($values, 'innerVariable');
		$this->viewHelper->render($values, 'innerVariable');
		$this->viewHelper->render($values, 'innerVariable');
	}

	/**
	 * @test
	 * @expectedException \F3\Fluid\Core\ViewHelper\Exception
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderThrowsExceptionWhenPassingObjectsToValuesThatAreNotTraversable() {
		$object = new \stdClass();

		$this->viewHelper->render($object, 'innerVariable');
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderReturnsChildNodesIfValuesIsNull() {
		$this->viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('Child nodes'));

		$this->assertEquals('Child nodes', $this->viewHelper->render(NULL, 'foo'));
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderReturnsChildNodesIfValuesIsAnEmptyArray() {
		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('foo', NULL);
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('foo');

		$this->viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('Child nodes'));

		$this->assertEquals('Child nodes', $this->viewHelper->render(array(), 'foo'));
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderIteratesThroughElementsOfTraversableObjects() {
		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('innerVariable', 'value1');
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('innerVariable');
		$this->templateVariableContainer->expects($this->at(2))->method('add')->with('innerVariable', 'value2');
		$this->templateVariableContainer->expects($this->at(3))->method('remove')->with('innerVariable');
		$this->templateVariableContainer->expects($this->at(4))->method('add')->with('innerVariable', 'value1');
		$this->templateVariableContainer->expects($this->at(5))->method('remove')->with('innerVariable');

		$traversableObject = new \ArrayObject(array('key1' => 'value1', 'key2' => 'value2'));
		$this->viewHelper->render($traversableObject, 'innerVariable');
		$this->viewHelper->render($traversableObject, 'innerVariable');
		$this->viewHelper->render($traversableObject, 'innerVariable');
	}
}

?>

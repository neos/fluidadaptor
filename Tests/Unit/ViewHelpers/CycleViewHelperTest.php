<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/ViewHelperBaseTestcase.php');

/**
 * Testcase for CycleViewHelper
 */
class CycleViewHelperTest extends \TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * @var \TYPO3\Fluid\ViewHelpers\CycleViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\CycleViewHelper', array('renderChildren'));
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 */
	public function renderAddsCurrentValueToTemplateVariableContainerAndRemovesItAfterRendering() {
		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('innerVariable', 'bar');
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('innerVariable');

		$values = array('bar', 'Fluid');
		$this->viewHelper->render($values, 'innerVariable');
	}

	/**
	 * @test
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
	 */
	public function viewHelperSupportsAssociativeArrays() {
		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('innerVariable', 'Flow');
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('innerVariable');
		$this->templateVariableContainer->expects($this->at(2))->method('add')->with('innerVariable', 'Fluid');
		$this->templateVariableContainer->expects($this->at(3))->method('remove')->with('innerVariable');
		$this->templateVariableContainer->expects($this->at(4))->method('add')->with('innerVariable', 'Flow');
		$this->templateVariableContainer->expects($this->at(5))->method('remove')->with('innerVariable');

		$values = array('foo' => 'Flow', 'bar' => 'Fluid');
		$this->viewHelper->render($values, 'innerVariable');
		$this->viewHelper->render($values, 'innerVariable');
		$this->viewHelper->render($values, 'innerVariable');
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Fluid\Core\ViewHelper\Exception
	 */
	public function renderThrowsExceptionWhenPassingObjectsToValuesThatAreNotTraversable() {
		$object = new \stdClass();

		$this->viewHelper->render($object, 'innerVariable');
	}

	/**
	 * @test
	 */
	public function renderReturnsChildNodesIfValuesIsNull() {
		$this->viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('Child nodes'));

		$this->assertEquals('Child nodes', $this->viewHelper->render(NULL, 'foo'));
	}

	/**
	 * @test
	 */
	public function renderReturnsChildNodesIfValuesIsAnEmptyArray() {
		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('foo', NULL);
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('foo');

		$this->viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('Child nodes'));

		$this->assertEquals('Child nodes', $this->viewHelper->render(array(), 'foo'));
	}

	/**
	 * @test
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
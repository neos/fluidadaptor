<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase;

require_once(__DIR__ . '/ViewHelperBaseTestcase.php');

/**
 * Testcase for CaseViewHelper
 */
class CaseViewHelperTest extends ViewHelperBaseTestcase {

	/**
	 * @var \TYPO3\Fluid\ViewHelpers\CaseViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = $this->getMock(\TYPO3\Fluid\ViewHelpers\CaseViewHelper::class, array('renderChildren'));
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Fluid\Core\ViewHelper\Exception
	 */
	public function renderThrowsExceptionIfSwitchExpressionIsNotSetInViewHelperVariableContainer() {
		$this->viewHelper->render('foo');
	}

	/**
	 * @test
	 */
	public function renderReturnsChildNodesIfTheSpecifiedValueIsEqualToTheSwitchExpression() {
		$this->viewHelperVariableContainerData = array(
			\TYPO3\Fluid\ViewHelpers\SwitchViewHelper::class => array(
				'switchExpression' => 'someValue',
			)
		);

		$renderedChildNodes = 'ChildNodes';
		$this->viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue($renderedChildNodes));

		$this->assertSame($renderedChildNodes, $this->viewHelper->render('someValue'));
	}

	/**
	 * @test
	 */
	public function renderSetsBreakStateInViewHelperVariableContainerIfTheSpecifiedValueIsEqualToTheSwitchExpression() {
		$this->viewHelperVariableContainerData = array(
			\TYPO3\Fluid\ViewHelpers\SwitchViewHelper::class => array(
				'switchExpression' => 'someValue',
			)
		);
		$this->viewHelperVariableContainer->expects($this->once())->method('addOrUpdate')->with(\TYPO3\Fluid\ViewHelpers\SwitchViewHelper::class, 'break', TRUE);

		$this->viewHelper->render('someValue');
	}

	/**
	 * @test
	 */
	public function renderWeaklyComparesSpecifiedValueWithSwitchExpression() {
		$numericValue = 123;
		$stringValue = '123';

		$this->viewHelperVariableContainerData = array(
			\TYPO3\Fluid\ViewHelpers\SwitchViewHelper::class => array(
				'switchExpression' => $numericValue,
			)
		);

		$this->viewHelperVariableContainer->expects($this->once())->method('addOrUpdate')->with(\TYPO3\Fluid\ViewHelpers\SwitchViewHelper::class, 'break', TRUE);

		$this->viewHelper->render($stringValue);
	}


	/**
	 * @test
	 */
	public function renderReturnsAnEmptyStringIfTheSpecifiedValueIsNotEqualToTheSwitchExpression() {
		$this->viewHelperVariableContainerData = array(
			\TYPO3\Fluid\ViewHelpers\SwitchViewHelper::class => array(
				'switchExpression' => 'someValue',
			)
		);
		$this->assertSame('', $this->viewHelper->render('someOtherValue'));
	}

}

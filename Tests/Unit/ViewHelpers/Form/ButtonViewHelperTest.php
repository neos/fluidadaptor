<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers\Form;

/*                                                                        *
 * This script belongs to the FLOW3 package "TYPO3.Fluid".                *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/FormFieldViewHelperBaseTestcase.php');

/**
 * Test for the "Button" Form view helper
 *
 */
class ButtonViewHelperTest extends \TYPO3\Fluid\Tests\Unit\ViewHelpers\Form\FormFieldViewHelperBaseTestcase {

	/**
	 * var \TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\Form\ButtonViewHelper', array('renderChildren'));
		$this->arguments['name'] = '';
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 */
	public function renderCorrectlySetsTagNameAndDefaultAttributes() {
		$mockTagBuilder = $this->getMock('TYPO3\Fluid\Core\ViewHelper\TagBuilder', array('setTagName', 'addAttribute', 'setContent'));
		$mockTagBuilder->expects($this->once())->method('setTagName')->with('button');
		$mockTagBuilder->expects($this->at(1))->method('addAttribute')->with('type', 'submit');
		$mockTagBuilder->expects($this->at(2))->method('addAttribute')->with('name', '');
		$mockTagBuilder->expects($this->at(3))->method('addAttribute')->with('value', '');
		$mockTagBuilder->expects($this->at(4))->method('setContent')->with('Button Content');

		$this->viewHelper->expects($this->atLeastOnce())->method('renderChildren')->will($this->returnValue('Button Content'));

		$this->viewHelper->injectTagBuilder($mockTagBuilder);

		$this->viewHelper->initialize();
		$this->viewHelper->render();
	}
}

?>
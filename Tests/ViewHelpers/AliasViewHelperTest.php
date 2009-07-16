<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\ViewHelpers;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

require_once(__DIR__ . '/ViewHelperBaseTestcase.php');
/**
 * Testcase for AliasViewHelper
 *
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */

class AliasViewHelperTest extends \F3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsSingleValueToTemplateVariableContainerAndRemovesItAfterRendering() {
		$viewHelper = new \F3\Fluid\ViewHelpers\AliasViewHelper();

		$mockViewHelperNode = $this->getMock('F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('evaluateChildNodes'), array(), '', FALSE);
		$mockViewHelperNode->expects($this->once())->method('evaluateChildNodes')->will($this->returnValue('foo'));

		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('someAlias', 'someValue');
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('someAlias');

		$viewHelper->setTemplateVariableContainer($this->templateVariableContainer);
		$viewHelper->setViewHelperNode($mockViewHelperNode);
		$viewHelper->render(array('someAlias' => 'someValue'));
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsMultipleValuesToTemplateVariableContainerAndRemovesThemAfterRendering() {
		$viewHelper = new \F3\Fluid\ViewHelpers\AliasViewHelper();

		$mockViewHelperNode = $this->getMock('F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('evaluateChildNodes'), array(), '', FALSE);
		$mockViewHelperNode->expects($this->once())->method('evaluateChildNodes')->will($this->returnValue('foo'));

		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('someAlias', 'someValue');
		$this->templateVariableContainer->expects($this->at(1))->method('add')->with('someOtherAlias', 'someOtherValue');
		$this->templateVariableContainer->expects($this->at(2))->method('remove')->with('someAlias');
		$this->templateVariableContainer->expects($this->at(3))->method('remove')->with('someOtherAlias');

		$viewHelper->setTemplateVariableContainer($this->templateVariableContainer);
		$viewHelper->setViewHelperNode($mockViewHelperNode);
		$viewHelper->render(array('someAlias' => 'someValue', 'someOtherAlias' => 'someOtherValue'));
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderDoesNotTouchTemplateVariableContainerAndReturnsChildNodesIfMapIsEmpty() {
		$viewHelper = new \F3\Fluid\ViewHelpers\AliasViewHelper();

		$mockViewHelperNode = $this->getMock('F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('evaluateChildNodes'), array(), '', FALSE);
		$mockViewHelperNode->expects($this->once())->method('evaluateChildNodes')->will($this->returnValue('foo'));

		$this->templateVariableContainer->expects($this->never())->method('add');
		$this->templateVariableContainer->expects($this->never())->method('remove');

		$viewHelper->setTemplateVariableContainer($this->templateVariableContainer);
		$viewHelper->setViewHelperNode($mockViewHelperNode);

		$this->assertEquals('foo', $viewHelper->render(array()));
	}
}



?>

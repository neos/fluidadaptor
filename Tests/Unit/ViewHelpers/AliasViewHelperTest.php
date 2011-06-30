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
 * Testcase for AliasViewHelper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class AliasViewHelperTest extends \TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsSingleValueToTemplateVariableContainerAndRemovesItAfterRendering() {
		$viewHelper = new \TYPO3\Fluid\ViewHelpers\AliasViewHelper();

		$mockViewHelperNode = $this->getMock('TYPO3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('evaluateChildNodes'), array(), '', FALSE);
		$mockViewHelperNode->expects($this->once())->method('evaluateChildNodes')->will($this->returnValue('foo'));

		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('someAlias', 'someValue');
		$this->templateVariableContainer->expects($this->at(1))->method('remove')->with('someAlias');

		$viewHelper->setTemplateVariableContainer($this->templateVariableContainer);
		$viewHelper->setViewHelperNode($mockViewHelperNode);
		$viewHelper->setRenderingContext($this->renderingContext);
		$viewHelper->render(array('someAlias' => 'someValue'));
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsMultipleValuesToTemplateVariableContainerAndRemovesThemAfterRendering() {
		$viewHelper = new \TYPO3\Fluid\ViewHelpers\AliasViewHelper();

		$mockViewHelperNode = $this->getMock('TYPO3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('evaluateChildNodes'), array(), '', FALSE);
		$mockViewHelperNode->expects($this->once())->method('evaluateChildNodes')->will($this->returnValue('foo'));

		$this->templateVariableContainer->expects($this->at(0))->method('add')->with('someAlias', 'someValue');
		$this->templateVariableContainer->expects($this->at(1))->method('add')->with('someOtherAlias', 'someOtherValue');
		$this->templateVariableContainer->expects($this->at(2))->method('remove')->with('someAlias');
		$this->templateVariableContainer->expects($this->at(3))->method('remove')->with('someOtherAlias');

		$viewHelper->setTemplateVariableContainer($this->templateVariableContainer);
		$viewHelper->setViewHelperNode($mockViewHelperNode);
		$viewHelper->setRenderingContext($this->renderingContext);
		$viewHelper->render(array('someAlias' => 'someValue', 'someOtherAlias' => 'someOtherValue'));
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderDoesNotTouchTemplateVariableContainerAndReturnsChildNodesIfMapIsEmpty() {
		$viewHelper = new \TYPO3\Fluid\ViewHelpers\AliasViewHelper();

		$mockViewHelperNode = $this->getMock('TYPO3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('evaluateChildNodes'), array(), '', FALSE);
		$mockViewHelperNode->expects($this->once())->method('evaluateChildNodes')->will($this->returnValue('foo'));

		$this->templateVariableContainer->expects($this->never())->method('add');
		$this->templateVariableContainer->expects($this->never())->method('remove');

		$viewHelper->setTemplateVariableContainer($this->templateVariableContainer);
		$viewHelper->setViewHelperNode($mockViewHelperNode);
		$viewHelper->setRenderingContext($this->renderingContext);

		$this->assertEquals('foo', $viewHelper->render(array()));
	}
}



?>

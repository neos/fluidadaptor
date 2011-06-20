<?php
namespace F3\Fluid\Tests\Unit\ViewHelpers\Form;

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

include_once(__DIR__ . '/../Fixtures/ConstraintSyntaxTreeNode.php');
require_once(__DIR__ . '/../ViewHelperBaseTestcase.php');

/**
 * Test for the Validation Results view helper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class ValidationResultsViewHelperTest extends \F3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * @test
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function renderWithoutSpecifiedNameLoopsThroughRootErrors() {
		$this->markTestIncomplete('Sebastian -- TODO after T3BOARD');
		$mockError1 = $this->getMock('F3\FLOW3\Error\Error', array(), array(), '', FALSE);
		$mockError2 = $this->getMock('F3\FLOW3\Error\Error', array(), array(), '', FALSE);
		$this->request->expects($this->atLeastOnce())->method('getErrors')->will($this->returnValue(array($mockError1, $mockError2)));

		$viewHelper = new \F3\Fluid\ViewHelpers\Form\ValidationResultsViewHelper();
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$variableContainer = new \F3\Fluid\Core\ViewHelper\TemplateVariableContainer(array());
		$viewHelperNode = new \F3\Fluid\ViewHelpers\Fixtures\ConstraintSyntaxTreeNode($variableContainer);
		$viewHelper->setViewHelperNode($viewHelperNode);
		$viewHelper->setTemplateVariableContainer($variableContainer);

		$viewHelper->render();

		$expectedCallProtocol = array(
			array('error' => $mockError1),
			array('error' => $mockError2)
		);
		$this->assertEquals($expectedCallProtocol, $viewHelperNode->callProtocol, 'The call protocol differs');
	}

}
?>
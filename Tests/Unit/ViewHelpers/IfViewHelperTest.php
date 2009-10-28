<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\ViewHelpers;

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
 * Testcase for IfViewHelper
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class IfViewHelperTest extends \F3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * var \F3\Fluid\ViewHelpers\IfViewHelper
	 */
	protected $viewHelper;

	/**
	 * var \F3\Fluid\Core\ViewHelper\Arguments
	 */
	protected $mockArguments;

	public function setUp() {
		parent::setUp();
		$this->viewHelper = $this->getMock($this->buildAccessibleProxy('F3\Fluid\ViewHelpers\IfViewHelper'), array('renderChildren'));
		$this->injectDependenciesIntoViewHelper($this->viewHelper);
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperRendersChildrenIfConditionIsTrueAndNoThenViewHelperChildExists() {
		$this->viewHelper->expects($this->at(0))->method('renderChildren')->will($this->returnValue('foo'));

		$actualResult = $this->viewHelper->render(TRUE);
		$this->assertEquals('foo', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperRendersThenViewHelperChildIfConditionIsTrueAndThenViewHelperChildExists() {
		$mockRenderingContext = $this->getMock('F3\Fluid\Core\Rendering\RenderingContext');

		$mockThenViewHelperNode = $this->getMock('F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('getViewHelperClassName', 'evaluate', 'setRenderingContext'), array(), '', FALSE);
		$mockThenViewHelperNode->expects($this->at(0))->method('getViewHelperClassName')->will($this->returnValue('F3\Fluid\ViewHelpers\ThenViewHelper'));
		$mockThenViewHelperNode->expects($this->at(1))->method('setRenderingContext')->with($mockRenderingContext);
		$mockThenViewHelperNode->expects($this->at(2))->method('evaluate')->will($this->returnValue('ThenViewHelperResults'));

		$this->viewHelper->setChildNodes(array($mockThenViewHelperNode));
		$this->viewHelper->setRenderingContext($mockRenderingContext);
		$actualResult = $this->viewHelper->render(TRUE);
		$this->assertEquals('ThenViewHelperResults', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderReturnsEmptyStringIfConditionIsFalseAndNoThenViewHelperChildExists() {
		$actualResult = $this->viewHelper->render(FALSE);
		$this->assertEquals('', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function viewHelperRendersElseViewHelperChildIfConditionIsFalseAndNoThenViewHelperChildExists() {
		$mockRenderingContext = $this->getMock('F3\Fluid\Core\Rendering\RenderingContext');

		$mockElseViewHelperNode = $this->getMock('F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('getViewHelperClassName', 'evaluate', 'setRenderingContext'), array(), '', FALSE);
		$mockElseViewHelperNode->expects($this->at(0))->method('getViewHelperClassName')->will($this->returnValue('F3\Fluid\ViewHelpers\ElseViewHelper'));
		$mockElseViewHelperNode->expects($this->at(1))->method('setRenderingContext')->with($mockRenderingContext);
		$mockElseViewHelperNode->expects($this->at(2))->method('evaluate')->will($this->returnValue('ElseViewHelperResults'));

		$this->viewHelper->setChildNodes(array($mockElseViewHelperNode));
		$this->viewHelper->setRenderingContext($mockRenderingContext);

		$actualResult = $this->viewHelper->render(FALSE);
		$this->assertEquals('ElseViewHelperResults', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderReturnsValueOfThenArgumentIfConditionIsTrue() {
		$this->arguments->expects($this->atLeastOnce())->method('hasArgument')->with('then')->will($this->returnValue(TRUE));
		$this->arguments->expects($this->atLeastOnce())->method('offsetGet')->with('then')->will($this->returnValue('ThenArgument'));

		$actualResult = $this->viewHelper->render(TRUE);
		$this->assertEquals('ThenArgument', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function thenArgumentHasPriorityOverChildNodesIfConditionIsTrue() {
		$mockRenderingContext = $this->getMock('F3\Fluid\Core\Rendering\RenderingContext');

		$mockThenViewHelperNode = $this->getMock('F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('getViewHelperClassName', 'evaluate', 'setRenderingContext'), array(), '', FALSE);
		$mockThenViewHelperNode->expects($this->never())->method('evaluate');

		$this->viewHelper->setChildNodes(array($mockThenViewHelperNode));
		$this->viewHelper->setRenderingContext($mockRenderingContext);

		$this->arguments->expects($this->atLeastOnce())->method('hasArgument')->with('then')->will($this->returnValue(TRUE));
		$this->arguments->expects($this->atLeastOnce())->method('offsetGet')->with('then')->will($this->returnValue('ThenArgument'));

		$actualResult = $this->viewHelper->render(TRUE);
		$this->assertEquals('ThenArgument', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderReturnsValueOfElseArgumentIfConditionIsFalse() {
		$this->arguments->expects($this->atLeastOnce())->method('hasArgument')->with('else')->will($this->returnValue(TRUE));
		$this->arguments->expects($this->atLeastOnce())->method('offsetGet')->with('else')->will($this->returnValue('ElseArgument'));

		$actualResult = $this->viewHelper->render(FALSE);
		$this->assertEquals('ElseArgument', $actualResult);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function elseArgumentHasPriorityOverChildNodesIfConditionIsFalse() {
		$mockRenderingContext = $this->getMock('F3\Fluid\Core\Rendering\RenderingContext');

		$mockElseViewHelperNode = $this->getMock('F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode', array('getViewHelperClassName', 'evaluate', 'setRenderingContext'), array(), '', FALSE);
		$mockElseViewHelperNode->expects($this->never())->method('evaluate');

		$this->viewHelper->setChildNodes(array($mockElseViewHelperNode));
		$this->viewHelper->setRenderingContext($mockRenderingContext);

		$this->arguments->expects($this->atLeastOnce())->method('hasArgument')->with('else')->will($this->returnValue(TRUE));
		$this->arguments->expects($this->atLeastOnce())->method('offsetGet')->with('else')->will($this->returnValue('ElseArgument'));

		$actualResult = $this->viewHelper->render(FALSE);
		$this->assertEquals('ElseArgument', $actualResult);
	}

}

?>

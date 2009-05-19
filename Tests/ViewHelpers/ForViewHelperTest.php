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

/**
 * @package Fluid
 * @subpackage Tests
 * @version $Id$
 */
/**
 * Testcase for DefaultViewHelper
 *
 * @package Fluid
 * @subpackage Tests
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */

include_once(__DIR__ . '/Fixtures/ConstraintSyntaxTreeNode.php');
class ForViewHelperTest extends \F3\Testing\BaseTestCase {

	/**
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function setUp() {
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function forExecutesTheLoopCorrectly() {
		$this->viewHelper = new \F3\Fluid\ViewHelpers\ForViewHelper();
		
		$variableContainer = new \F3\Fluid\Core\ViewHelper\TemplateVariableContainer(array());
		
		$viewHelperNode = new \F3\Fluid\ViewHelpers\Fixtures\ConstraintSyntaxTreeNode($variableContainer);		
		$this->viewHelper->setVariableContainer($variableContainer);
		$this->viewHelper->setViewHelperNode($viewHelperNode);
		$this->viewHelper->render(array(0,1,2,3), 'innerVariable');
		
		$expectedCallProtocol = array(
			array('innerVariable' => 0),
			array('innerVariable' => 1),
			array('innerVariable' => 2),
			array('innerVariable' => 3)
		);
		$this->assertEquals($expectedCallProtocol, $viewHelperNode->callProtocol, 'The call protocol differs -> The for loop does not work as it should!');	
	}
}



?>

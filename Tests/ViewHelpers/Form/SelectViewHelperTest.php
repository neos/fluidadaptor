<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\ViewHelpers\Form;

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
 * @package
 * @subpackage
 * @version $Id$
 */

include_once(__DIR__ . '/Fixtures/EmptySyntaxTreeNode.php');
include_once(__DIR__ . '/Fixtures/Fixture_UserDomainClass.php');

/**
 * Test for the "Select" Form view helper
 *
 * @package
 * @subpackage
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class SelectViewHelperTest extends \F3\Testing\BaseTestCase {

	/**
	 * var \F3\Fluid\ViewHelpers\Form\SelectViewHelper
	 */
	protected $viewHelper;

	public function setUp() {
		$this->viewHelper = new \F3\Fluid\ViewHelpers\Form\SelectViewHelper();
		$this->viewHelper->initializeArguments();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function selectCorrectlySetsTagName() {
		$tagBuilderMock = $this->getMock('F3\Fluid\Core\TagBuilder', array('setTagName'), array(), '', FALSE);
		$tagBuilderMock->expects($this->once())->method('setTagName')->with('select');
		$this->viewHelper->injectTagBuilder($tagBuilderMock);
		$this->viewHelper->arguments = new \F3\Fluid\Core\ViewHelperArguments(array('options' => array()));

		$this->viewHelper->initialize();
		$this->viewHelper->render();
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function selectCreatesExpectedOptions() {
		$tagBuilderMock = $this->getMock('F3\Fluid\Core\TagBuilder', array('addAttribute', 'setContent', 'render'), array(), '', FALSE);
		$tagBuilderMock->expects($this->once())->method('addAttribute')->with('name', 'myName');
		$tagBuilderMock->expects($this->once())->method('setContent')->with('<option value="value1">label1</option>' . chr(10) . '<option value="value2" selected="selected">label2</option>' . chr(10));
		$tagBuilderMock->expects($this->once())->method('render');
		$this->viewHelper->injectTagBuilder($tagBuilderMock);

		$arguments = new \F3\Fluid\Core\ViewHelperArguments(array(
			'options' => array(
				'value1' => 'label1',
				'value2' => 'label2'
			),
			'value' => 'value2',
			'name' => 'myName'
		));

		$this->viewHelper->arguments = $arguments;
		$this->viewHelper->initialize();
		$this->viewHelper->render();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function multipleSelectCreatesExpectedOptions() {
		$tagBuilderMock = $this->getMock('F3\Fluid\Core\TagBuilder', array('addAttribute', 'setContent', 'render'), array(), '', FALSE);
		$tagBuilderMock->expects($this->at(0))->method('addAttribute')->with('multiple', 'multiple');
		$tagBuilderMock->expects($this->at(1))->method('addAttribute')->with('name', 'myName[]');
		$tagBuilderMock->expects($this->once())->method('setContent')->with('<option value="value1" selected="selected">label1</option>' . chr(10) . '<option value="value2">label2</option>' . chr(10) . '<option value="value3" selected="selected">label3</option>' . chr(10));
		$tagBuilderMock->expects($this->once())->method('render');
		$this->viewHelper->injectTagBuilder($tagBuilderMock);

		$arguments = new \F3\Fluid\Core\ViewHelperArguments(array(
			'options' => array(
				'value1' => 'label1',
				'value2' => 'label2',
				'value3' => 'label3'
			),
			'value' => array('value3', 'value1'),
			'name' => 'myName',
			'multiple' => 'multiple',
		));

		$this->viewHelper->arguments = $arguments;
		$this->viewHelper->initialize();
		$this->viewHelper->render();
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function selectOnDomainObjectsCreatesExpectedOptions() {
		$tagBuilderMock = $this->getMock('F3\Fluid\Core\TagBuilder', array('addAttribute', 'setContent', 'render'), array(), '', FALSE);
		$tagBuilderMock->expects($this->once())->method('addAttribute')->with('name', 'myName');
		$tagBuilderMock->expects($this->once())->method('setContent')->with('<option value="1">Ingmar</option>' . chr(10) . '<option value="2" selected="selected">Sebastian</option>' . chr(10) . '<option value="3">Robert</option>' . chr(10));
		$tagBuilderMock->expects($this->once())->method('render');
		$this->viewHelper->injectTagBuilder($tagBuilderMock);

		$user_is = new \F3\Fluid\ViewHelpers\Fixtures\UserDomainClass(1, 'Ingmar', 'Schlecht');
		$user_sk = new \F3\Fluid\ViewHelpers\Fixtures\UserDomainClass(2, 'Sebastian', 'Kurfuerst');
		$user_rl = new \F3\Fluid\ViewHelpers\Fixtures\UserDomainClass(3, 'Robert', 'Lemke');
		$arguments = new \F3\Fluid\Core\ViewHelperArguments(array(
			'options' => array(
				$user_is,
				$user_sk,
				$user_rl
			),
			'value' => $user_sk,
			'optionValueField' => 'id',
			'optionLabelField' => 'firstName',
			'name' => 'myName'
		));

		$this->viewHelper->arguments = $arguments;
		$this->viewHelper->initialize();
		$this->viewHelper->render();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function multipleSelectOnDomainObjectsCreatesExpectedOptions() {
		$tagBuilderMock = $this->getMock('F3\Fluid\Core\TagBuilder', array('addAttribute', 'setContent', 'render'), array(), '', FALSE);
		$tagBuilderMock->expects($this->at(0))->method('addAttribute')->with('multiple', 'multiple');
		$tagBuilderMock->expects($this->at(1))->method('addAttribute')->with('name', 'myName[]');
		$tagBuilderMock->expects($this->once())->method('setContent')->with('<option value="1" selected="selected">Schlecht</option>' . chr(10) . '<option value="2">Kurfuerst</option>' . chr(10) . '<option value="3" selected="selected">Lemke</option>' . chr(10));
		$tagBuilderMock->expects($this->once())->method('render');
		$this->viewHelper->injectTagBuilder($tagBuilderMock);

		$user_is = new \F3\Fluid\ViewHelpers\Fixtures\UserDomainClass(1, 'Ingmar', 'Schlecht');
		$user_sk = new \F3\Fluid\ViewHelpers\Fixtures\UserDomainClass(2, 'Sebastian', 'Kurfuerst');
		$user_rl = new \F3\Fluid\ViewHelpers\Fixtures\UserDomainClass(3, 'Robert', 'Lemke');
		$arguments = new \F3\Fluid\Core\ViewHelperArguments(array(
			'options' => array(
				$user_is,
				$user_sk,
				$user_rl
			),
			'value' => array($user_rl, $user_is),
			'optionValueField' => 'id',
			'optionLabelField' => 'lastName',
			'name' => 'myName',
			'multiple' => 'multiple'
		));

		$this->viewHelper->arguments = $arguments;
		$this->viewHelper->initialize();
		$this->viewHelper->render();
	}
}

?>
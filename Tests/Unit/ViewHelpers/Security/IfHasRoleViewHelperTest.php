<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers\Security;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

require_once(__DIR__ . '/../ViewHelperBaseTestcase.php');

/**
 * Testcase for IfHasRoleViewHelper
 *
 */
class IfHasRoleViewHelperTest extends \TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase {

	/**
	 * Create a mock controllerContext
	 *
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getMockControllerContext() {
		$httpRequest = \TYPO3\Flow\Http\Request::create(new \TYPO3\Flow\Http\Uri('http://robertlemke.com/blog'));
		$mockRequest = $this->getMock('TYPO3\Flow\Mvc\ActionRequest', array(), array($httpRequest));
		$mockRequest->expects($this->any())->method('getControllerPackageKey')->will($this->returnValue('TYPO3.Fluid'));

		$mockControllerContext = $this->getMock('TYPO3\Flow\Mvc\Controller\ControllerContext', array('getRequest'), array(), '', FALSE);
		$mockControllerContext->expects($this->any())->method('getRequest')->will($this->returnValue($mockRequest));

		return $mockControllerContext;
	}

	/**
	 * @test
	 */
	public function viewHelperRendersThenPartIfHasRoleReturnsTrue() {
		$mockSecurityContext = $this->getMock('TYPO3\Flow\Security\Context', array(), array(), '', FALSE);
		$mockSecurityContext->expects($this->once())->method('hasRole')->with('TYPO3.Fluid.someGA')->will($this->returnValue(TRUE));

		$mockViewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Security\IfHasRoleViewHelper', array('renderThenChild', 'hasAccessToResource'));
		$mockViewHelper->injectSecurityContext($mockSecurityContext);
		$this->inject($mockViewHelper, 'controllerContext', $this->getMockControllerContext());
		$mockViewHelper->expects($this->once())->method('renderThenChild')->will($this->returnValue('foo'));

		$actualResult = $mockViewHelper->render('someGA');
		$this->assertEquals('foo', $actualResult);
	}

	/**
	 * @test
	 */
	public function viewHelperHandlesPackageKeyAttributeCorrectly() {
		$mockSecurityContext = $this->getMock('TYPO3\Flow\Security\Context', array(), array(), '', FALSE);
		$mockSecurityContext->expects($this->any())->method('hasRole')->will($this->returnCallback(function($role) {
			switch($role) {
				case 'TYPO3.Fluid.Administrator':
					return TRUE;
				case 'TYPO3.Fluid.User':
					return FALSE;
				case 'Everybody':
					return TRUE;
				default:
					return FALSE;
			}
		}));

		$mockViewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Security\IfHasRoleViewHelper', array('renderThenChild', 'renderElseChild', 'hasAccessToResource'));
		$mockViewHelper->injectSecurityContext($mockSecurityContext);
		$this->inject($mockViewHelper, 'controllerContext', $this->getMockControllerContext());
		$mockViewHelper->expects($this->any())->method('renderThenChild')->will($this->returnValue('true'));
		$mockViewHelper->expects($this->any())->method('renderElseChild')->will($this->returnValue('false'));

		$actualResult = $mockViewHelper->render('TYPO3.Fluid.Administrator');
		$this->assertEquals('true', $actualResult, 'Full role identifier in role argument is accepted');

		$actualResult = $mockViewHelper->render('Administrator');
		$this->assertEquals('true', $actualResult, 'Packagekey from controllerContext is automatically prepended if packageKey is absent');

		$actualResult = $mockViewHelper->render('Everybody');
		$this->assertEquals('true', $actualResult);

		$actualResult = $mockViewHelper->render('Administrator', 'TYPO3.Fluid');
		$this->assertEquals('true', $actualResult);

		$actualResult = $mockViewHelper->render('User', 'TYPO3.Fluid');
		$this->assertEquals('false', $actualResult);
	}

	/**
	 * @test
	 */
	public function viewHelperRendersElsePartIfConditionIsFalse() {
		$mockSecurityContext = $this->getMock('TYPO3\Flow\Security\Context', array(), array(), '', FALSE);
		$mockSecurityContext->expects($this->once())->method('hasRole')->with('TYPO3.Fluid.someGA')->will($this->returnValue(FALSE));

		$mockViewHelper = $this->getMock('TYPO3\Fluid\ViewHelpers\Security\IfHasRoleViewHelper', array('renderElseChild'));
		$mockViewHelper->expects($this->once())->method('renderElseChild')->will($this->returnValue('ElseViewHelperResults'));
		$mockViewHelper->injectSecurityContext($mockSecurityContext);
		$this->inject($mockViewHelper, 'controllerContext', $this->getMockControllerContext());

		$actualResult = $mockViewHelper->render('someGA');
		$this->assertEquals('ElseViewHelperResults', $actualResult);
	}
}

?>

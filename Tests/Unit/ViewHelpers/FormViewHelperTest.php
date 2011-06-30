<?php
namespace TYPO3\Fluid\Tests\Unit\ViewHelpers;

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

require_once(__DIR__ . '/ViewHelperBaseTestcase.php');

/**
 * Test for the Form view helper
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class FormViewHelperTest extends \TYPO3\Fluid\ViewHelpers\ViewHelperBaseTestcase {
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function renderAddsObjectToViewHelperVariableContainer() {
		$formObject = new \stdClass();

		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren', 'renderHiddenIdentityField', 'renderAdditionalIdentityFields', 'renderHiddenReferrerFields', 'addFormObjectNameToViewHelperVariableContainer', 'addFieldNamePrefixToViewHelperVariableContainer', 'removeFormObjectNameFromViewHelperVariableContainer', 'removeFieldNamePrefixFromViewHelperVariableContainer', 'addFormFieldNamesToViewHelperVariableContainer', 'removeFormFieldNamesFromViewHelperVariableContainer'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$viewHelper->setArguments(new \TYPO3\Fluid\Core\ViewHelper\Arguments(array('object' => $formObject)));
		$this->viewHelperVariableContainer->expects($this->at(0))->method('add')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObject', $formObject);
		$this->viewHelperVariableContainer->expects($this->at(1))->method('add')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties', array());
		$this->viewHelperVariableContainer->expects($this->at(2))->method('remove')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObject');
		$this->viewHelperVariableContainer->expects($this->at(3))->method('remove')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties');
		$viewHelper->render();
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsObjectNameToTemplateVariableContainer() {
		$objectName = 'someObjectName';

		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren', 'renderHiddenIdentityField', 'renderHiddenReferrerFields', 'addFormObjectToViewHelperVariableContainer', 'addFieldNamePrefixToViewHelperVariableContainer', 'removeFormObjectFromViewHelperVariableContainer', 'removeFieldNamePrefixFromViewHelperVariableContainer', 'addFormFieldNamesToViewHelperVariableContainer', 'removeFormFieldNamesFromViewHelperVariableContainer'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$viewHelper->setArguments(new \TYPO3\Fluid\Core\ViewHelper\Arguments(array('name' => $objectName)));

		$this->viewHelperVariableContainer->expects($this->once())->method('add')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName', $objectName);
		$this->viewHelperVariableContainer->expects($this->once())->method('remove')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
		$viewHelper->render();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function formObjectNameArgumentOverrulesNameArgument() {
		$objectName = 'someObjectName';

		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren', 'renderHiddenIdentityField', 'renderHiddenReferrerFields', 'addFormObjectToViewHelperVariableContainer', 'addFieldNamePrefixToViewHelperVariableContainer', 'removeFormObjectFromViewHelperVariableContainer', 'removeFieldNamePrefixFromViewHelperVariableContainer', 'addFormFieldNamesToViewHelperVariableContainer', 'removeFormFieldNamesFromViewHelperVariableContainer'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$viewHelper->setArguments(new \TYPO3\Fluid\Core\ViewHelper\Arguments(array('name' => 'formName', 'objectName' => $objectName)));

		$this->viewHelperVariableContainer->expects($this->once())->method('add')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName', $objectName);
		$this->viewHelperVariableContainer->expects($this->once())->method('remove')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
		$viewHelper->render();
	}

	/**
	 * @test
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function renderCallsRenderHiddenReferrerFields() {
		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren', 'renderHiddenReferrerFields'), array(), '', FALSE);
		$viewHelper->expects($this->once())->method('renderHiddenReferrerFields');
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$viewHelper->render();
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderCallsRenderHiddenIdentityField() {
		$this->markTestIncomplete('Sebastian -- TODO after T3BOARD');
		$object = new \stdClass();
		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren', 'renderHiddenIdentityField', 'getFormObjectName'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$mockObjectSerializer = $this->getMock('TYPO3\FLOW3\Object\ObjectSerializer');
		$viewHelper->injectObjectSerializer($mockObjectSerializer);

		$viewHelper->setArguments(new \TYPO3\Fluid\Core\ViewHelper\Arguments(array('object' => $object)));
		$viewHelper->expects($this->atLeastOnce())->method('getFormObjectName')->will($this->returnValue('MyName'));
		$viewHelper->expects($this->once())->method('renderHiddenIdentityField')->with($object, 'MyName');

		$viewHelper->render();
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function renderCallsRenderAdditionalIdentityFields() {
		$this->markTestIncomplete('Sebastian -- TODO after T3BOARD');
		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren', 'renderAdditionalIdentityFields'), array(), '', FALSE);
		$viewHelper->expects($this->once())->method('renderAdditionalIdentityFields');
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$mockObjectSerializer = $this->getMock('TYPO3\FLOW3\Object\ObjectSerializer');
		$viewHelper->injectObjectSerializer($mockObjectSerializer);

		$viewHelper->render();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderWrapsHiddenFieldsWithDivForXhtmlCompatibility() {
		$viewHelper = $this->getMock($this->buildAccessibleProxy('TYPO3\Fluid\ViewHelpers\FormViewHelper'), array('renderChildren', 'renderHiddenIdentityField', 'renderAdditionalIdentityFields', 'renderHiddenReferrerFields'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);
		$viewHelper->expects($this->once())->method('renderHiddenIdentityField')->will($this->returnValue('hiddenIdentityField'));
		$viewHelper->expects($this->once())->method('renderAdditionalIdentityFields')->will($this->returnValue('additionalIdentityFields'));
		$viewHelper->expects($this->once())->method('renderHiddenReferrerFields')->will($this->returnValue('hiddenReferrerFields'));
		$viewHelper->expects($this->once())->method('renderChildren')->will($this->returnValue('formContent'));

		$expectedResult = chr(10) . '<div style="display: none">' . 'hiddenIdentityFieldadditionalIdentityFieldshiddenReferrerFields' . chr(10) . '</div>' . chr(10) . 'formContent';
		$this->tagBuilder->expects($this->once())->method('setContent')->with($expectedResult);

		$viewHelper->render();
	}


	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function renderAdditionalIdentityFieldsFetchesTheFieldsFromViewHelperVariableContainerAndBuildsHiddenFieldsForThem() {
		$identityProperties = array(
			'object1[object2]' => '<input type="hidden" name="object1[object2][__identity]" value="42" />',
			'object1[object2][subobject]' => '<input type="hidden" name="object1[object2][subobject][__identity]" value="21" />'
		);
		$this->viewHelperVariableContainer->expects($this->once())->method('exists')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties')->will($this->returnValue(TRUE));
		$this->viewHelperVariableContainer->expects($this->once())->method('get')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties')->will($this->returnValue($identityProperties));
		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$expected = chr(10) . '<input type="hidden" name="object1[object2][__identity]" value="42" />' . chr(10) .
			'<input type="hidden" name="object1[object2][subobject][__identity]" value="21" />';
		$actual = $viewHelper->_call('renderAdditionalIdentityFields');
		$this->assertEquals($expected, $actual);
	}

	/**
	 * @test
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function renderHiddenReferrerFieldsAddCurrentControllerAndActionAsHiddenFields() {
		$this->markTestIncomplete('Sebastian -- TODO after T3BOARD');
		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('dummy'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$mockObjectSerializer = $this->getMock('TYPO3\FLOW3\Object\ObjectSerializer');
		$viewHelper->injectObjectSerializer($mockObjectSerializer);

		$this->request->expects($this->atLeastOnce())->method('getControllerPackageKey')->will($this->returnValue('packageKey'));
		$this->request->expects($this->atLeastOnce())->method('getControllerSubpackageKey')->will($this->returnValue('subpackageKey'));
		$this->request->expects($this->atLeastOnce())->method('getControllerName')->will($this->returnValue('controllerName'));
		$this->request->expects($this->atLeastOnce())->method('getControllerActionName')->will($this->returnValue('controllerActionName'));

		$hiddenFields = $viewHelper->_call('renderHiddenReferrerFields');
		$expectedResult = chr(10) . '<input type="hidden" name="__referrer[packageKey]" value="packageKey" />' . chr(10) .
			'<input type="hidden" name="__referrer[subpackageKey]" value="subpackageKey" />' . chr(10) .
			'<input type="hidden" name="__referrer[controllerName]" value="controllerName" />' . chr(10) .
			'<input type="hidden" name="__referrer[actionName]" value="controllerActionName" />' . chr(10);
		$this->assertEquals($expectedResult, $hiddenFields);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderHiddenReferrerFieldsAddCurrentControllerAndActionOfParentAndSubRequestAsHiddenFields() {
		$this->markTestIncomplete('Sebastian -- TODO after T3BOARD');
		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('dummy'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$mockObjectSerializer = $this->getMock('TYPO3\FLOW3\Object\ObjectSerializer');
		$viewHelper->injectObjectSerializer($mockObjectSerializer);

		$mockSubRequest = $this->getMock('TYPO3\FLOW3\MVC\Web\SubRequest', array(), array(), '', FALSE);
		$mockSubRequest->expects($this->atLeastOnce())->method('getControllerPackageKey')->will($this->returnValue('subRequestPackageKey'));
		$mockSubRequest->expects($this->atLeastOnce())->method('getControllerSubpackageKey')->will($this->returnValue('subRequestSubpackageKey'));
		$mockSubRequest->expects($this->atLeastOnce())->method('getControllerName')->will($this->returnValue('subRequestControllerName'));
		$mockSubRequest->expects($this->atLeastOnce())->method('getControllerActionName')->will($this->returnValue('subRequestControllerActionName'));
		$mockSubRequest->expects($this->any())->method('getParentRequest')->will($this->returnValue($this->request));
		$mockSubRequest->expects($this->any())->method('getArgumentNamespace')->will($this->returnValue('subRequestArgumentNamespace'));

		$this->request->expects($this->atLeastOnce())->method('getControllerPackageKey')->will($this->returnValue('packageKey'));
		$this->request->expects($this->atLeastOnce())->method('getControllerSubpackageKey')->will($this->returnValue('subpackageKey'));
		$this->request->expects($this->atLeastOnce())->method('getControllerName')->will($this->returnValue('controllerName'));
		$this->request->expects($this->atLeastOnce())->method('getControllerActionName')->will($this->returnValue('controllerActionName'));

		$this->controllerContext = $this->getMock('TYPO3\FLOW3\MVC\Controller\ControllerContext', array(), array(), '', FALSE);
		$this->controllerContext->expects($this->any())->method('getUriBuilder')->will($this->returnValue($this->uriBuilder));
		$this->controllerContext->expects($this->any())->method('getRequest')->will($this->returnValue($mockSubRequest));
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$hiddenFields = $viewHelper->_call('renderHiddenReferrerFields');
		$expectedResult = chr(10) . '<input type="hidden" name="subRequestArgumentNamespace[__referrer][packageKey]" value="subRequestPackageKey" />' . chr(10) .
			'<input type="hidden" name="subRequestArgumentNamespace[__referrer][subpackageKey]" value="subRequestSubpackageKey" />' . chr(10) .
			'<input type="hidden" name="subRequestArgumentNamespace[__referrer][controllerName]" value="subRequestControllerName" />' . chr(10) .
			'<input type="hidden" name="subRequestArgumentNamespace[__referrer][actionName]" value="subRequestControllerActionName" />' . chr(10) .
			'<input type="hidden" name="__referrer[packageKey]" value="packageKey" />' . chr(10) .
			'<input type="hidden" name="__referrer[subpackageKey]" value="subpackageKey" />' . chr(10) .
			'<input type="hidden" name="__referrer[controllerName]" value="controllerName" />' . chr(10) .
			'<input type="hidden" name="__referrer[actionName]" value="controllerActionName" />' . chr(10);

		$this->assertEquals($expectedResult, $hiddenFields);
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsSpecifiedPrefixToTemplateVariableContainer() {
		$prefix = 'somePrefix';

		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren', 'renderHiddenIdentityField', 'renderHiddenReferrerFields', 'addFormFieldNamesToViewHelperVariableContainer', 'removeFormFieldNamesFromViewHelperVariableContainer'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$viewHelper->setArguments(new \TYPO3\Fluid\Core\ViewHelper\Arguments(array('fieldNamePrefix' => $prefix)));

		$this->viewHelperVariableContainer->expects($this->once())->method('add')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix', $prefix);
		$this->viewHelperVariableContainer->expects($this->once())->method('remove')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix');
		$viewHelper->render();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsNoFieldNamePrefixToTemplateVariableContainerIfNoPrefixIsSpecified() {
		$expectedPrefix = '';

		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren', 'renderHiddenIdentityField', 'renderHiddenReferrerFields', 'addFormFieldNamesToViewHelperVariableContainer', 'removeFormFieldNamesFromViewHelperVariableContainer'), array(), '', FALSE);
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$this->viewHelperVariableContainer->expects($this->once())->method('add')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix', $expectedPrefix);
		$this->viewHelperVariableContainer->expects($this->once())->method('remove')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix');
		$viewHelper->render();
	}

	/**
	 * @test
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function renderAddsDefaultFieldNamePrefixToTemplateVariableContainerIfNoPrefixIsSpecifiedAndRequestIsASubRequest() {
		$expectedPrefix = 'someArgumentPrefix';
		$mockSubRequest = $this->getMock('TYPO3\FLOW3\MVC\Web\SubRequest', array(), array(), '', FALSE);
		$mockSubRequest->expects($this->once())->method('getArgumentNamespace')->will($this->returnValue($expectedPrefix));

		$viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\FormViewHelper', array('renderChildren', 'renderHiddenIdentityField', 'renderHiddenReferrerFields', 'addFormFieldNamesToViewHelperVariableContainer', 'removeFormFieldNamesFromViewHelperVariableContainer'), array(), '', FALSE);
		$this->controllerContext = $this->getMock('TYPO3\FLOW3\MVC\Controller\ControllerContext', array(), array(), '', FALSE);
		$this->controllerContext->expects($this->any())->method('getUriBuilder')->will($this->returnValue($this->uriBuilder));
		$this->controllerContext->expects($this->any())->method('getRequest')->will($this->returnValue($mockSubRequest));
		$this->injectDependenciesIntoViewHelper($viewHelper);

		$this->viewHelperVariableContainer->expects($this->once())->method('add')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix', $expectedPrefix);
		$this->viewHelperVariableContainer->expects($this->once())->method('remove')->with('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix');
		$viewHelper->render();
	}

}
?>
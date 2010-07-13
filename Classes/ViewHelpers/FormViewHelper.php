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

/**
 * Form view helper. Generates a <form> Tag.
 *
 * = Basic usage =
 *
 * Use <f:form> to output an HTML <form> tag which is targeted at the specified action, in the current controller and package.
 * It will submit the form data via a POST request. If you want to change this, use method="get" as an argument.
 * <code title="Example">
 * <f:form action="...">...</f:form>
 * </code>
 *
 * = A complex form with a specified encoding type =
 *
 * <code title="Form with enctype set">
 * <f:form action=".." controller="..." package="..." enctype="multipart/form-data">...</f:form>
 * </code>
 *
 * = A Form which should render a domain object =
 *
 * <code title="Binding a domain object to a form">
 * <f:form action="..." name="customer" object="{customer}">
 *   <f:form.hidden property="id" />
 *   <f:form.textbox property="name" />
 * </f:form>
 * </code>
 * This automatically inserts the value of {customer.name} inside the textbox and adjusts the name of the textbox accordingly.
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class FormViewHelper extends \F3\Fluid\ViewHelpers\Form\AbstractFormViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'form';

	/**
	 * @var F3\FLOW3\Security\Channel\RequestHashService
	 */
	protected $requestHashService;

	/**
	 * Inject a request hash service
	 *
	 * @param F3\FLOW3\Security\Channel\RequestHashService $requestHashService The request hash service
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function injectRequestHashService(\F3\FLOW3\Security\Channel\RequestHashService $requestHashService) {
		$this->requestHashService = $requestHashService;
	}

	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function initializeArguments() {
		$this->registerTagAttribute('enctype', 'string', 'MIME type with which the form is submitted');
		$this->registerTagAttribute('method', 'string', 'Transfer type (GET or POST)');
		$this->registerTagAttribute('name', 'string', 'Name of form');
		$this->registerTagAttribute('onreset', 'string', 'JavaScript: On reset of the form');
		$this->registerTagAttribute('onsubmit', 'string', 'JavaScript: On submit of the form');

		$this->registerUniversalTagAttributes();
	}

	/**
	 * Render the form.
	 *
	 * @param string $action target action
	 * @param array $arguments additional arguments
	 * @param string $controller name of target controller
	 * @param string $package name of target package
	 * @param string $subpackage name of target subpackage
	 * @param mixed $object object to use for the form. Use in conjunction with the "property" attribute on the sub tags
	 * @param string $section The anchor to be added to the action URI (only active if $actionUri is not set)
	 * @param string $format The requested format (e.g. ".html") of the target page (only active if $actionUri is not set)
	 * @param array $additionalParams additional action URI query parameters that won't be prefixed like $arguments (overrule $arguments) (only active if $actionUri is not set)
	 * @param boolean $absolute If set, an absolute action URI is rendered (only active if $actionUri is not set)
	 * @param boolean $addQueryString If set, the current query parameters will be kept in the action URI (only active if $actionUri is not set)
	 * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the action URI. Only active if $addQueryString = TRUE and $actionUri is not set
	 * @param string $fieldNamePrefix Prefix that will be added to all field names within this form
	 * @param string $actionUri can be used to overwrite the "action" attribute of the form tag
	 * @return string rendered form
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function render($action = '', array $arguments = array(), $controller = NULL, $package = NULL, $subpackage = NULL, $object = NULL, $section = '', $format = '', array $additionalParams = array(), $absolute = FALSE, $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $fieldNamePrefix = NULL, $actionUri = NULL) {
		$this->setFormActionUri();

		if (strtolower($this->arguments['method']) === 'get') {
			$this->tag->addAttribute('method', 'get');
		} else {
			$this->tag->addAttribute('method', 'post');
		}

		$this->addFormNameToViewHelperVariableContainer();
		$this->addFormObjectToViewHelperVariableContainer();
		$this->addFieldNamePrefixToViewHelperVariableContainer();
		$this->addFormFieldNamesToViewHelperVariableContainer();

		$formContent = $this->renderChildren();

		$content = $this->renderHiddenIdentityField($this->arguments['object'], $this->arguments['name']);
		$content .= $this->renderAdditionalIdentityFields();
		$content .= $this->renderHiddenReferrerFields();
		$content .= $this->renderRequestHashField(); // Render hmac after everything else has been rendered
		$content .= $formContent;

		$this->tag->setContent($content);

		$this->removeFieldNamePrefixFromViewHelperVariableContainer();
		$this->removeFormObjectFromViewHelperVariableContainer();
		$this->removeFormNameFromViewHelperVariableContainer();
		$this->removeFormFieldNamesFromViewHelperVariableContainer();

		return $this->tag->render();
	}

	/**
	 * Sets the "action" attribute of the form tag
	 *
	 * @return void
	 */
	protected function setFormActionUri() {
		if ($this->arguments->hasArgument('actionUri')) {
			$formActionUri = $this->arguments['actionUri'];
		} else {
			$uriBuilder = $this->controllerContext->getUriBuilder();
			$uriBuilder
				->reset()
				->setSection($this->arguments['section'])
				->setCreateAbsoluteUri($this->arguments['absolute'])
				->setAddQueryString($this->arguments['addQueryString'])
				->setFormat($this->arguments['format']);
			if (is_array($this->arguments['additionalParams'])) {
				$uriBuilder->setArguments($this->arguments['additionalParams']);
			}
			if (is_array($this->arguments['argumentsToBeExcludedFromQueryString'])) {
				$uriBuilder->setArgumentsToBeExcludedFromQueryString($this->arguments['argumentsToBeExcludedFromQueryString']);
			}
			$formActionUri = $uriBuilder
				->uriFor($this->arguments['action'], $this->arguments['arguments'], $this->arguments['controller'], $this->arguments['package'], $this->arguments['subpackage']);
		}
		$this->tag->addAttribute('action', $formActionUri);
	}

	/**
	 * Render additional identity fields which were registered by form elements.
	 * This happens if a form field is defined like property="bla.blubb" - then we might need an identity property for the sub-object "bla".
	 *
	 * @return string HTML-string for the additional identity properties
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function renderAdditionalIdentityFields() {
		if ($this->viewHelperVariableContainer->exists('F3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties')) {
			$additionalIdentityProperties = $this->viewHelperVariableContainer->get('F3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties');
			$output = '';
			foreach ($additionalIdentityProperties as $identity) {
				$output .= chr(10) . $identity;
			}
			return $output;
		}
		return '';
	}

	/**
	 * Renders hidden form fields for referrer information about
	 * the current controller and action.
	 *
	 * @return string Hidden fields with referrer information
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 * @todo filter out referrer information that is equal to the target (e.g. same packageKey)
	 */
	protected function renderHiddenReferrerFields() {
		$request = $this->controllerContext->getRequest();
		$packageKey = $request->getControllerPackageKey();
		$subpackageKey = $request->getControllerSubpackageKey();
		$controllerName = $request->getControllerName();
		$actionName = $request->getControllerActionName();
		$result = chr(10);
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[packageKey]') . '" value="' . $packageKey . '" />' . chr(10);
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[subpackageKey]') . '" value="' . $subpackageKey . '" />' . chr(10);
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[controllerName]') . '" value="' . $controllerName . '" />' . chr(10);
		$result .= '<input type="hidden" name="' . $this->prefixFieldName('__referrer[actionName]') . '" value="' . $actionName . '" />' . chr(10);
		return $result;
	}

	/**
	 * Adds the form name to the ViewHelperVariableContainer if the name attribute is specified.
	 *
	 * @return void
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function addFormNameToViewHelperVariableContainer() {
		if ($this->arguments->hasArgument('name')) {
			$this->viewHelperVariableContainer->add('F3\Fluid\ViewHelpers\FormViewHelper', 'formName', $this->arguments['name']);
		}
	}

	/**
	 * Removes the form name from the ViewHelperVariableContainer.
	 *
	 * @return void
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function removeFormNameFromViewHelperVariableContainer() {
		if ($this->arguments->hasArgument('name')) {
			$this->viewHelperVariableContainer->remove('F3\Fluid\ViewHelpers\FormViewHelper', 'formName');
		}
	}

	/**
	 * Adds the object that is bound to this form to the ViewHelperVariableContainer if the formObject attribute is specified.
	 *
	 * @return void
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function addFormObjectToViewHelperVariableContainer() {
		if ($this->arguments->hasArgument('object')) {
			$this->viewHelperVariableContainer->add('F3\Fluid\ViewHelpers\FormViewHelper', 'formObject', $this->arguments['object']);
			$this->viewHelperVariableContainer->add('F3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties', array());
		}
	}

	/**
	 * Removes the form object from the ViewHelperVariableContainer.
	 *
	 * @return void
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function removeFormObjectFromViewHelperVariableContainer() {
		if ($this->arguments->hasArgument('object')) {
			$this->viewHelperVariableContainer->remove('F3\Fluid\ViewHelpers\FormViewHelper', 'formObject');
			$this->viewHelperVariableContainer->remove('F3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties');
		}
	}

	/**
	 * Adds the field name prefix to the ViewHelperVariableContainer
	 *
	 * @return void
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function addFieldNamePrefixToViewHelperVariableContainer() {
		$fieldNamePrefix = '';
		if ($this->arguments->hasArgument('fieldNamePrefix')) {
			$fieldNamePrefix = $this->arguments['fieldNamePrefix'];
		}
		$this->viewHelperVariableContainer->add('F3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix', $fieldNamePrefix);
	}

	/**
	 * Removes field name prefix from the ViewHelperVariableContainer
	 *
	 * @return void
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function removeFieldNamePrefixFromViewHelperVariableContainer() {
		$this->viewHelperVariableContainer->remove('F3\Fluid\ViewHelpers\FormViewHelper', 'fieldNamePrefix');
	}

	/**
	 * Adds a container for form field names to the ViewHelperVariableContainer
	 *
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function addFormFieldNamesToViewHelperVariableContainer() {
		$this->viewHelperVariableContainer->add('F3\Fluid\ViewHelpers\FormViewHelper', 'formFieldNames', array());
	}

	/**
	 * Removes the container for form field names from the ViewHelperVariableContainer
	 *
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function removeFormFieldNamesFromViewHelperVariableContainer() {
		$this->viewHelperVariableContainer->remove('F3\Fluid\ViewHelpers\FormViewHelper', 'formFieldNames');
	}

	/**
	 * Render the request hash field
	 *
	 * @return string the hmac field
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function renderRequestHashField() {
		$formFieldNames = $this->viewHelperVariableContainer->get('F3\Fluid\ViewHelpers\FormViewHelper', 'formFieldNames');
		$this->postProcessUriArgumentsForRequesthash($this->controllerContext->getUriBuilder()->getLastArguments(), $formFieldNames);
		$requestHash = $this->requestHashService->generateRequestHash($formFieldNames);
		return '<input type="hidden" name="__hmac" value="' . htmlspecialchars($requestHash) . '" />';
	}

	/**
	 * Add the URI arguments after postprocessing to the request hash as well.
	 */
	protected function postProcessUriArgumentsForRequestHash($arguments, &$results, $currentPrefix = '', $level = 0) {
		if (!count($arguments)) return;
		foreach ($arguments as $argumentName => $argumentValue) {
			if (is_array($argumentValue)) {
				$prefix = ($level==0 ? $argumentName : $currentPrefix . '[' . $argumentName . ']');
				$this->postProcessUriArgumentsForRequestHash($argumentValue, $results, $prefix, $level+1);
			} else {
				$results[] = ($level==0 ? $argumentName : $currentPrefix . '[' . $argumentName . ']');
			}
		}
	}

}

?>
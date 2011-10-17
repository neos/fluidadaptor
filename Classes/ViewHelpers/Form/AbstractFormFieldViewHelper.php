<?php
namespace TYPO3\Fluid\ViewHelpers\Form;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 *  of the License, or (at your option) any later version.                *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Abstract Form View Helper. Bundles functionality related to direct property access of objects in other Form ViewHelpers.
 *
 * If you set the "property" attribute to the name of the property to resolve from the object, this class will
 * automatically set the name and value of a form element.
 *
 * @api
 * @FLOW3\Scope("prototype")
 */
abstract class AbstractFormFieldViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\AbstractFormViewHelper {

	/**
	 * Initialize arguments.
	 *
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('name', 'string', 'Name of input tag');
		$this->registerArgument('value', 'mixed', 'Value of input tag');
		$this->registerArgument('property', 'string', 'Name of Object Property. If used in conjunction with <f:form object="...">, "name" and "value" properties will be ignored.');
	}

	/**
	 * Get the name of this form element.
	 * Either returns arguments['name'], or the correct name for Object Access.
	 *
	 * In case property is something like bla.blubb (hierarchical), then [bla][blubb] is generated.
	 *
	 * @return string Name
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function getName() {
		$name = $this->getNameWithoutPrefix();
		return $this->prefixFieldName($name);
	}

	/**
	 * Get the name of this form element, without prefix.
	 *
	 * @return string name
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function getNameWithoutPrefix() {
		if ($this->isObjectAccessorMode()) {
			$formObjectName = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
			if (!empty($formObjectName)) {
				$propertySegments = explode('.', $this->arguments['property']);
				$propertyPath = '';
				foreach ($propertySegments as $segment) {
					$propertyPath .= '[' . $segment . ']';
				}
				$name = $formObjectName . $propertyPath;
			} else {
				$name = $this->arguments['property'];
			}
		} else {
			$name = $this->arguments['name'];
		}
		if ($this->hasArgument('value') && is_object($this->arguments['value'])) {
			if (NULL !== $this->persistenceManager->getIdentifierByObject($this->arguments['value'])
				&& (!$this->persistenceManager->isNewObject($this->arguments['value']))) {
				$name .= '[__identity]';
			}
		}

		return $name;
	}

	/**
	 * Get the value of this form element.
	 * Either returns arguments['value'], or the correct value for Object Access.
	 *
	 * @return mixed Value
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function getValue() {
		$value = NULL;
		if ($this->hasArgument('value')) {
			$value = $this->arguments['value'];
		} elseif ($this->hasMappingErrorOccured()) {
			$value = $this->getLastSubmittedFormData();
		} elseif ($this->isObjectAccessorMode() && $this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObject')) {
			$this->addAdditionalIdentityPropertiesIfNeeded();
			$value = $this->getPropertyValue();
		}
		if (is_object($value)) {
			$identifier = $this->persistenceManager->getIdentifierByObject($value);
			if ($identifier !== NULL) {
				$value = $identifier;
			}
		}
		return $value;
	}

	/**
	 * Checks if a property mapping error has occured in the last request.
	 *
	 * @return boolean TRUE if a mapping error occured, FALSE otherwise
	 */
	protected function hasMappingErrorOccured() {
		return ($this->controllerContext->getRequest()->getOriginalRequest() !== NULL);
	}

	/**
	 * Get the form data which has last been submitted; only returns valid data in case
	 * a property mapping error has occured. Check with hasMappingErrorOccured() before!
	 *
	 * @return mixed
	 */
	protected function getLastSubmittedFormData() {
		$propertyPath = rtrim(preg_replace('/(\]\[|\[|\])/', '.', $this->getNameWithoutPrefix()), '.');
		$value = \TYPO3\FLOW3\Reflection\ObjectAccess::getPropertyPath($this->controllerContext->getRequest()->getOriginalRequest()->getArguments(), $propertyPath);
		return $value;
	}

	/**
	 * Add additional identity properties in case the current property is hierarchical (of the form "bla.blubb").
	 * Then, [bla][__identity] has to be generated as well.
	 *
	 * @author Sebastian Kurfuerst <sebastian@typo3.org>
	 * @return void
	 */
	protected function addAdditionalIdentityPropertiesIfNeeded() {
		$propertySegments = explode('.', $this->arguments['property']);
		if (count($propertySegments) >= 2) {
				// hierarchical property. If there is no "." inside (thus $propertySegments == 1), we do not need to do anything
			$formObject = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObject');

			$objectName = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
				// If Count == 2 -> we need to go through the for-loop exactly once
			for ($i=1; $i < count($propertySegments); $i++) {
				$object = \TYPO3\FLOW3\Reflection\ObjectAccess::getPropertyPath($formObject, implode('.', array_slice($propertySegments, 0, $i)));
				$objectName .= '[' . $propertySegments[$i-1] . ']';
				$hiddenIdentityField = $this->renderHiddenIdentityField($object, $objectName);

					// Add the hidden identity field to the ViewHelperVariableContainer
				$additionalIdentityProperties = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties');
				$additionalIdentityProperties[$objectName] = $hiddenIdentityField;
				$this->viewHelperVariableContainer->addOrUpdate('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'additionalIdentityProperties', $additionalIdentityProperties);
			}
		}
	}

	/**
	 * Get the current property of the object bound to this form.
	 *
	 * @return mixed Value
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function getPropertyValue() {

		$formObject = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObject');
		$propertyName = $this->arguments['property'];

		if (is_array($formObject)) {
			return isset($formObject[$propertyName]) ? $formObject[$propertyName] : NULL;
		}
		return \TYPO3\FLOW3\Reflection\ObjectAccess::getPropertyPath($formObject, $propertyName);
	}

	/**
	 * Internal method which checks if we should evaluate a domain object or just output arguments['name'] and arguments['value']
	 *
	 * @return boolean TRUE if we should evaluate the domain object, FALSE otherwise.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function isObjectAccessorMode() {
		return $this->hasArgument('property')
			&& $this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');
	}

	/**
	 * Add an CSS class if this view helper has errors
	 *
	 * @return void
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function setErrorClassAttribute() {
		if ($this->hasArgument('class')) {
			$cssClass = $this->arguments['class'] . ' ';
		} else {
			$cssClass = '';
		}
		$mappingResultsForProperty = $this->getMappingResultsForProperty();
		if ($mappingResultsForProperty->hasErrors()) {
			if ($this->hasArgument('errorClass')) {
				$cssClass .= $this->arguments['errorClass'];
			} else {
				$cssClass .= 'error';
			}
			$this->tag->addAttribute('class', $cssClass);
		}
	}

	/**
	 * Get errors for the property and form name of this view helper
	 *
	 * @return array<\TYPO3\FLOW3\Error\Error> Array of errors
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function getMappingResultsForProperty() {
		if (!$this->isObjectAccessorMode()) {
			return new \TYPO3\FLOW3\Error\Result();
		}
		$originalRequestMappingResults = $this->controllerContext->getRequest()->getOriginalRequestMappingResults();
		$formObjectName = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'formObjectName');

		return $originalRequestMappingResults->forProperty($formObjectName)->forProperty($this->arguments['property']);
	}

	/**
	 * Renders a hidden field with the same name as the element, to make sure the empty value is submitted
	 * in case nothing is selected. This is needed for checkbox and multiple select fields
	 *
	 * @return string the hidden field.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function renderHiddenFieldForEmptyValue() {
		$hiddenFieldNames = array();
		if ($this->viewHelperVariableContainer->exists('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'renderedHiddenFields')) {
			$hiddenFieldNames = $this->viewHelperVariableContainer->get('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'renderedHiddenFields');
		}

		$fieldName = $this->getName();
		if (substr($fieldName, -2) === '[]') {
			$fieldName = substr($fieldName, 0, -2);
		}
		if (!in_array($fieldName, $hiddenFieldNames)) {
			$hiddenFieldNames[] = $fieldName;
			$this->viewHelperVariableContainer->addOrUpdate('TYPO3\Fluid\ViewHelpers\FormViewHelper', 'renderedHiddenFields', $hiddenFieldNames);

			return '<input type="hidden" name="' . htmlspecialchars($fieldName) . '" value="" />';
		}
		return '';
	}
}

?>
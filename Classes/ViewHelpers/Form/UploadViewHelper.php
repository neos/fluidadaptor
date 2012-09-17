<?php
namespace TYPO3\Fluid\ViewHelpers\Form;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * A view helper which generates an <input type="file"> HTML element.
 * Make sure to set enctype="multipart/form-data" on the form!
 *
 * If a file has been uploaded successfully and the form is re-displayed due to validation errors,
 * this ViewHelper will render hidden fields that contain the previously generated resource so you
 * won't have to upload the file again.
 *
 * You can use a separate ViewHelper to display previously uploaded resources in order to remove/replace them.
 *
 * = Examples =
 *
 * <code title="Example">
 * <f:form.upload name="file" />
 * </code>
 * <output>
 * <input type="file" name="file" />
 * </output>
 *
 * <code title="Multiple Uploads">
 * <f:form.upload property="attachments.0.originalResource" />
 * <f:form.upload property="attachments.1.originalResource" />
 * </code>
 * <output>
 * <input type="file" name="formObject[attachments][0][originalResource]">
 * <input type="file" name="formObject[attachments][0][originalResource]">
 * </output>
 *
 * @api
 */
class UploadViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'input';

	/**
	 * @var \TYPO3\FLOW3\Property\PropertyMapper
	 * @FLOW3\Inject
	 */
	protected $propertyMapper;

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerTagAttribute('disabled', 'string', 'Specifies that the input element should be disabled when the page loads');
		$this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', FALSE, 'f3-form-error');
		$this->registerUniversalTagAttributes();
	}

	/**
	 * Renders the upload field.
	 *
	 * @return string
	 * @api
	 */
	public function render() {
		$name = $this->getName();
		$this->registerFieldNameForFormTokenGeneration($name);

		$output = '';
		$resourceObject = $this->getUploadedResource();
		if ($resourceObject !== NULL) {
			$filenameIdAttribute = $resourcePointerIdAttribute = '';
			if ($this->hasArgument('id')) {
				$filenameIdAttribute = ' id="' . htmlspecialchars($this->arguments['id']) . '-filename"';
				$resourcePointerIdAttribute = ' id="' . htmlspecialchars($this->arguments['id']) . '-resourcePointer"';
			}
			$filenameValue = $resourceObject->getFilename();
			$resourcePointerValue = $resourceObject->getResourcePointer();
			$output .= '<input type="hidden" name="'. $this->getName() . '[submittedFile][filename]" value="' . htmlspecialchars($filenameValue) . '"' . $filenameIdAttribute . ' />';
			$output .= '<input type="hidden" name="'. $this->getName() . '[submittedFile][resourcePointer]" value="' . htmlspecialchars($resourcePointerValue) . '"' . $resourcePointerIdAttribute . ' />';
		}

		$this->tag->addAttribute('type', 'file');
		$this->tag->addAttribute('name', $name);

		$this->setErrorClassAttribute();

		$output .= $this->tag->render();
		return $output;
	}

	/**
	 * Returns a previously uploaded resource.
	 * If errors occurred during property mapping for this property, NULL is returned
	 *
	 * @return \TYPO3\FLOW3\Resource\Resource
	 */
	protected function getUploadedResource() {
		if ($this->getMappingResultsForProperty()->hasErrors()) {
			return NULL;
		}
		$resourceObject = $this->getValue(FALSE);
		if ($resourceObject instanceof \TYPO3\FLOW3\Resource\Resource) {
			return $resourceObject;
		}
		return $this->propertyMapper->convert($resourceObject, 'TYPO3\FLOW3\Resource\Resource');
	}
}


?>

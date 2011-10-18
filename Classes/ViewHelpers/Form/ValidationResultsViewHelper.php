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


/**
 * Validation results view helper
 *
 * = Examples =
 *
 * <code title="Output error messages as a list">
 * <f:form.validationResults>
 *   <f:if condition="{validationResults.flattenedErrors}">
 *     <ul class="errors">
 *       <f:for each="{errors}" as="error">
 *         <li>{error.code}: {error}</li>
 *       </f:for>
 *     </ul>
 *   </f:if>
 * </f:form.validationResults>
 * </code>
 * <output>
 * <ul class="errors">
 *   <li>1234567890: Validation errors for argument "newBlog"</li>
 * </ul>
 * </output>
 *
 * <code title="Output error messages for a single property">
 * <f:form.validationResults for="someProperty">
 *   <f:if condition="{validationResults.flattenedErrors}">
 *     <ul class="errors">
 *       <f:for each="{errors}" as="error">
 *         <li>{error.code}: {error}</li>
 *       </f:for>
 *     </ul>
 *   </f:if>
 * </f:form.validationResults>
 * </code>
 * <output>
 * <ul class="errors">
 *   <li>1234567890: Some error message</li>
 * </ul>
 * </output>
 *
 * @api
 */
class ValidationResultsViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Iterates through selected errors of the request.
	 *
	 * @param string $for The name of the error name (e.g. argument name or property name). This can also be a property path (like blog.title), and will then only display the validation errors of that property.
	 * @param string $as The name of the variable to store the current error
	 * @return string Rendered string
	 * @api
	 */
	public function render($for = '', $as = 'validationResults') {
		$validationResults = $this->controllerContext->getRequest()->getOriginalRequestMappingResults();

		if ($for !== '') {
			$validationResults = $validationResults->forProperty($for);
		}
		$this->templateVariableContainer->add($as, $validationResults);
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove($as);

		return $output;
	}
}

?>
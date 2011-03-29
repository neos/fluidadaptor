<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\ViewHelpers\Form;

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
 * Error messages view helper
 *
 * = Examples =
 *
 * <code title="Output error messages as a list">
 * <ul class="errors">
 *   <f:form.errors>
 *     <li>{error.code}: {error.message}</li>
 *   </f:form.errors>
 * </ul>
 * </code>
 * <output>
 * <ul>
 *   <li>1234567890: Validation errors for argument "newBlog"</li>
 * </ul>
 * </output>
 *
 * <code title="Output error messages for a single property">
 * <f:form.errors for="someProperty">
 *   <div class="error">
 *     <strong>{error.propertyName}</strong>: <f:for each="{error.errors}" as="errorDetail">{errorDetail.message}</f:for>
 *   </div>
 * </f:form.errors>
 * </code>
 * <output>
 * <div class="error>
 *   <strong>someProperty:</strong> errorMessage1 errorMessage2
 * </div>
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class ValidationResultsViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Iterates through selected errors of the request.
	 *
	 * @param string $for The name of the error name (e.g. argument name or property name). This can also be a property path (like blog.title), and will then only display the validation errors of that property.
	 * @param string $as The name of the variable to store the current error
	 * @return string Rendered string
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
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
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
 *   <f:errors>
 *     <li>{error.code}: {error.message}</li>
 *   </f:errors>
 * </ul>
 * </code>
 *
 * Output:
 * <ul>
 *   <li>1234567890: Validation errors for argument "newBlog"</li>
 * </ul>
 *
 * @version $Id: ForViewHelper.php 2378 2009-05-25 20:47:00Z sebastian $
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class ErrorsViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Iterates through selected errors of the request.
	 *
	 * @param string $for The name of the error name (e.g. argument name or property name)
	 * @param string $as The name of the variable to store the current error
	 * @return string Rendered string
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 * @api
	 */
	public function render($for = '', $as = 'error') {
		$errors = $this->controllerContext->getRequest()->getErrors();
		if ($for !== '') {
			$errors = $this->getErrorsForProperty($for, $errors);
		}
		$output = '';
		foreach ($errors as $errorKey => $error) {
			$this->templateVariableContainer->add($as, $error);
			$output .= $this->renderChildren();
			$this->templateVariableContainer->remove($as);
		}
		return $output;
	}

	/**
	 * Find errors for a specific property in the given errors array
	 *
	 * @param string $propertyName The property name to look up
	 * @param array $errors An array of F3\FLOW3\Error\Error objects
	 * @return array An array of errors for $propertyName
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	protected function getErrorsForProperty($propertyName, $errors) {
		foreach ($errors as $error) {
			if ($error instanceof \F3\FLOW3\Validation\PropertyError) {
				if ($error->getPropertyName() === $propertyName) {
					return $error->getErrors();
				}
			}
		}
		return array();
	}
}

?>
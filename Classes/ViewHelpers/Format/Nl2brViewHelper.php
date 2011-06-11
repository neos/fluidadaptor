<?php
namespace F3\Fluid\ViewHelpers\Format;

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
 * Wrapper for PHPs nl2br function.
 * @see http://www.php.net/manual/en/function.nl2br.php
 *
 * = Examples =
 *
 * <code title="Example">
 * <f:format.nl2br>{text_with_linebreaks}</f:format.nl2br>
 * </code>
 * <output>
 * text with line breaks replaced by <br />
 * </output>
 *
 * <code title="Inline notation">
 * {text_with_linebreaks -> f:format.nl2br()}
 * </code>
 * <output>
 * text with line breaks replaced by <br />
 * </output>
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class Nl2brViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Replaces newline characters by HTML line breaks.
	 *
	 * @return string the altered string.
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function render() {
		$content = $this->renderChildren();
		return nl2br($content);
	}
}
?>
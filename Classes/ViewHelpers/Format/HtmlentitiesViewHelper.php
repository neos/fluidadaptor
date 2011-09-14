<?php
namespace TYPO3\Fluid\ViewHelpers\Format;

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
 * Applies htmlentities() escaping to a value
 * @see http://www.php.net/manual/function.htmlentities.php
 *
 * = Examples =
 *
 * <code title="default notation">
 * <f:format.htmlentities>{text}</f:format.htmlentities>
 * </code>
 * <output>
 * Text with & " ' < > * replaced by HTML entities (htmlentities applied).
 * </output>
 *
 * <code title="inline notation">
 * {text -> f:format.htmlentities(encoding: 'ISO-8859-1')}
 * </code>
 * <output>
 * Text with & " ' < > * replaced by HTML entities (htmlentities applied).
 * </output>
 *
 * @scope singleton
 * @api
 */
class HtmlentitiesViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Disable the escaping interceptor because otherwise the child nodes would be escaped before this view helper
	 * can decode the text's entities.
	 *
	 * @var boolean
	 */
	protected $escapingInterceptorEnabled = FALSE;

	/**
	 * Escapes special characters with their escaped counterparts as needed using PHPs htmlentities() function.
	 *
	 * @param string $value string to format
	 * @param boolean $keepQuotes if TRUE, single and double quotes won't be replaced (sets ENT_NOQUOTES flag)
	 * @param string $encoding
	 * @param boolean $doubleEncode If FALSE existing html entities won't be encoded, the default is to convert everything.
	 * @return string the altered string
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @see http://www.php.net/manual/function.htmlentities.php
	 * @api
	 */
	public function render($value = NULL, $keepQuotes = FALSE, $encoding = 'UTF-8', $doubleEncode = TRUE) {
		if ($value === NULL) {
			$value = $this->renderChildren();
		}
		if (!is_string($value)) {
			return $value;
		}
		$flags = $keepQuotes ? ENT_NOQUOTES : ENT_COMPAT;
		return htmlentities($value, $flags, $encoding, $doubleEncode);
	}
}
?>
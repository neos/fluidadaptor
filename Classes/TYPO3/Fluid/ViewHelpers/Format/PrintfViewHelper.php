<?php
namespace TYPO3\Fluid\ViewHelpers\Format;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A view helper for formatting values with printf. Either supply an array for
 * the arguments or a single value.
 * See http://www.php.net/manual/en/function.sprintf.php
 *
 * = Examples =
 *
 * <code title="Scientific notation">
 * <f:format.printf arguments="{number: 362525200}">%.3e</f:format.printf>
 * </code>
 * <output>
 * 3.625e+8
 * </output>
 *
 * <code title="Argument swapping">
 * <f:format.printf arguments="{0: 3, 1: 'Kasper'}">%2$s is great, TYPO%1$d too. Yes, TYPO%1$d is great and so is %2$s!</f:format.printf>
 * </code>
 * <output>
 * Kasper is great, TYPO3 too. Yes, TYPO3 is great and so is Kasper!
 * </output>
 *
 * <code title="Single argument">
 * <f:format.printf arguments="{1: 'TYPO3'}">We love %s</f:format.printf>
 * </code>
 * <output>
 * We love TYPO3
 * </output>
 *
 * <code title="Inline notation">
 * {someText -> f:format.printf(arguments: {1: 'TYPO3'})}
 * </code>
 * <output>
 * We love TYPO3
 * </output>
 *
 * @api
 */
class PrintfViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Format the arguments with the given printf format string.
	 *
	 * @param array $arguments The arguments for vsprintf
	 * @return string The formatted value
	 * @api
	 */
	public function render(array $arguments) {
		$format = $this->renderChildren();
		return vsprintf($format, $arguments);
	}
}

?>
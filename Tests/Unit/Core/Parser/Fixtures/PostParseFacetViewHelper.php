<?php
namespace TYPO3\Fluid\Core\Parser\Fixtures;

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
 * Enter description here...
 * @scope prototype
 */
class PostParseFacetViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper implements \TYPO3\Fluid\Core\ViewHelper\Facets\PostParseInterface {

	public static $wasCalled = FALSE;

	public function __construct() {
	}

	static public function postParseEvent(\TYPO3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode $viewHelperNode, array $arguments, \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer $variableContainer) {
		self::$wasCalled = TRUE;
	}

	public function initializeArguments() {
	}

	public function render() {
	}
}

?>
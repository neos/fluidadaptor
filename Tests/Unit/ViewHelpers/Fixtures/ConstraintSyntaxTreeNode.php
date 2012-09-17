<?php
namespace TYPO3\Fluid\ViewHelpers\Fixtures;

/*                                                                        *
 * This script belongs to the FLOW3 package "Fluid".                      *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * [Enter description here]
 *
 */
class ConstraintSyntaxTreeNode extends \TYPO3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode {
	public $callProtocol = array();

	public function __construct(\TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer $variableContainer) {
		$this->variableContainer = $variableContainer;
	}

	public function evaluateChildNodes(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
		$identifiers = $this->variableContainer->getAllIdentifiers();
		$callElement = array();
		foreach ($identifiers as $identifier) {
			$callElement[$identifier] = $this->variableContainer->get($identifier);
		}
		$this->callProtocol[] = $callElement;
	}

	public function evaluate(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {}
}


?>

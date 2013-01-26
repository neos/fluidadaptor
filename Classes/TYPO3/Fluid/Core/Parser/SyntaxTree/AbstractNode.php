<?php
namespace TYPO3\Fluid\Core\Parser\SyntaxTree;

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
 * Abstract node in the syntax tree which has been built.
 */
abstract class AbstractNode implements \TYPO3\Fluid\Core\Parser\SyntaxTree\NodeInterface {

	/**
	 * List of Child Nodes.
	 *
	 * @var array<\TYPO3\Fluid\Core\Parser\SyntaxTree\NodeInterface>
	 */
	protected $childNodes = array();

	/**
	 * Evaluate all child nodes and return the evaluated results.
	 *
	 * @param \TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 * @return mixed Normally, an object is returned - in case it is concatenated with a string, a string is returned.
	 * @throws \TYPO3\Fluid\Core\Parser\Exception
	 */
	public function evaluateChildNodes(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
		$output = NULL;
		foreach ($this->childNodes as $subNode) {
			if ($output === NULL) {
				$output = $subNode->evaluate($renderingContext);
			} else {
				if (is_object($output)) {
					if (!method_exists($output, '__toString')) {
						throw new \TYPO3\Fluid\Core\Parser\Exception('Cannot cast object of type "' . get_class($output) . '" to string.', 1248356140);
					}
					$output = $output->__toString();
				} else {
					$output = (string) $output;
				}
				$subNodeOutput = $subNode->evaluate($renderingContext);

				if (is_object($subNodeOutput)) {
					if (!method_exists($subNodeOutput, '__toString')) {
						throw new \TYPO3\Fluid\Core\Parser\Exception('Cannot cast object of type "' . get_class($subNodeOutput) . '" to string.', 1273753083);
					}
					$output .= $subNodeOutput->__toString();
				} else {
					$output .= (string) $subNodeOutput;
				}
			}
		}
		return $output;
	}

	/**
	 * Returns all child nodes for a given node.
	 * This is especially needed to implement the boolean expression language.
	 *
	 * @return array<\TYPO3\Fluid\Core\Parser\SyntaxTree\NodeInterface> A list of nodes
	 */
	public function getChildNodes() {
		return $this->childNodes;
	}

	/**
	 * Appends a subnode to this node. Is used inside the parser to append children
	 *
	 * @param \TYPO3\Fluid\Core\Parser\SyntaxTree\NodeInterface $childNode The subnode to add
	 * @return void
	 */
	public function addChildNode(\TYPO3\Fluid\Core\Parser\SyntaxTree\NodeInterface $childNode) {
		$this->childNodes[] = $childNode;
	}
}

?>
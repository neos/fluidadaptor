<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Core\Parser;

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
 * Stores all information relevant for one parsing pass - that is, the root node,
 * and the current stack of open nodes (nodeStack) and a variable container used
 * for PostParseFacets.
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class ParsingState implements \F3\Fluid\Core\Parser\ParsedTemplateInterface {

	/**
	 * Root node reference
	 * @var \F3\Fluid\Core\Parser\SyntaxTree\RootNode
	 */
	protected $rootNode;

	/**
	 * Array of node references currently open.
	 * @var array
	 */
	protected $nodeStack = array();

	/**
	 * Variable container where ViewHelpers implementing the PostParseFacet can
	 * store things in.
	 * @var \F3\Fluid\Core\ViewHelper\TemplateVariableContainer
	 */
	protected $variableContainer;

	/**
	 * Injects a variable container. ViewHelpers implementing the PostParse
	 * Facet can store information inside this variableContainer.
	 *
	 * @param \F3\Fluid\Core\ViewHelper\TemplateVariableContainer $variableContainer
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function injectVariableContainer(\F3\Fluid\Core\ViewHelper\TemplateVariableContainer $variableContainer) {
		$this->variableContainer = $variableContainer;
	}

	/**
	 * Set root node of this parsing state
	 *
	 * @param \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $rootNode
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function setRootNode(\F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $rootNode) {
		$this->rootNode = $rootNode;
	}

	/**
	 * Get root node of this parsing state.
	 *
	 * @return \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode The root node
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function getRootNode() {
		return $this->rootNode;
	}

	/**
	 * Render the parsed template with rendering context
	 *
	 * @param F3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext The rendering context to use
	 * @return Rendered string
	 */
	public function render(\F3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
		return $this->rootNode->evaluate($renderingContext);
	}

	/**
	 * Push a node to the node stack. The node stack holds all currently open
	 * templating tags.
	 *
	 * @param \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $node Node to push to node stack
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function pushNodeToStack(\F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $node) {
		array_push($this->nodeStack, $node);
	}

	/**
	 * Get the top stack element, without removing it.
	 *
	 * @return \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode the top stack element.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function getNodeFromStack() {
		return $this->nodeStack[count($this->nodeStack)-1];
	}

	/**
	 * Pop the top stack element (=remove it) and return it back.
	 *
	 * @return \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode the top stack element, which was removed.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function popNodeFromStack() {
		return array_pop($this->nodeStack);
	}

	/**
	 * Count the size of the node stack
	 *
	 * @return integer Number of elements on the node stack (i.e. number of currently open Fluid tags)
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function countNodeStack() {
		return count($this->nodeStack);
	}

	/**
	 * Returns a variable container which will be then passed to the postParseFacet.
	 *
	 * @return \F3\Fluid\Core\ViewHelper\TemplateVariableContainer The variable container or NULL if none has been set yet
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @todo Rename to getPostParseVariableContainer
	 */
	public function getVariableContainer() {
		return $this->variableContainer;
	}
}
?>
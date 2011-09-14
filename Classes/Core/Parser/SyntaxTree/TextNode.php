<?php
namespace TYPO3\Fluid\Core\Parser\SyntaxTree;

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
 * Text Syntax Tree Node - is a container for strings.
 *
 * @scope prototype
 */
class TextNode extends \TYPO3\Fluid\Core\Parser\SyntaxTree\AbstractNode {

	/**
	 * Contents of the text node
	 * @var string
	 */
	protected $text;

	/**
	 * Constructor.
	 *
	 * @param string $text text to store in this textNode
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function __construct($text) {
		if (!is_string($text)) {
			throw new \TYPO3\Fluid\Core\Parser\Exception('Text node requires an argument of type string, "' . gettype($text) . '" given.');
		}
		$this->text = $text;
	}

	/**
	 * Return the text associated to the syntax tree. Text from child nodes is
	 * appended to the text in the node's own text.
	 *
	 * @param \TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 * @return string the text stored in this node/subtree.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function evaluate(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
		return $this->text . $this->evaluateChildNodes($renderingContext);
	}

	/**
	 * Getter for text
	 *
	 * @return string The text of this node
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function getText() {
		return $this->text;
	}
}

?>
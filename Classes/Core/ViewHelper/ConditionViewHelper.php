<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Core\ViewHelper;

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
 * This view helper is an abstract ViewHelper which implements an if/else condition.
 * @see F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode::convertArgumentValue() to find see how boolean arguments are evaluated
 *
 * = Usage =
 *
 * To create a custom Condition ViewHelper, you need to subclass this class, and
 * implement your own render() method. Inside there, you should call $this->renderThenChild()
 * if the condition evaluated to TRUE, and $this->renderElseChild() if the condition evaluated
 * to FALSE.
 *
 * Every Condition ViewHelper has a "then" and "else" argument, so it can be used like:
 * <[aConditionViewHelperName] .... then="condition true" else="condition false" />,
 * or as well use the "then" and "else" child nodes.
 *
 * @see F3\Fluid\ViewHelpers\IfViewHelper for a more detailed explanation and a simple usage example.
 * Make sure to NOT OVERRIDE the constructor.
 *
 * @version $Id: IfViewHelper.php 4671 2010-06-30 08:25:50Z robert $
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
abstract class ConditionViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper implements \F3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface {

	/**
	 * An array of \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode
	 * @var array
	 */
	private $childNodes = array();

	/**
	 * Setter for ChildNodes - as defined in ChildNodeAccessInterface
	 *
	 * @param array $childNodes Child nodes of this syntax tree node
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function setChildNodes(array $childNodes) {
		$this->childNodes = $childNodes;
	}

	/**
	 * Initializes the "then" and "else" arguments
	 *
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function __construct() {
		$this->registerArgument('then', 'mixed', 'Value to be returned if the condition if met.', FALSE);
		$this->registerArgument('else', 'mixed', 'Value to be returned if the condition if not met.', FALSE);
	}

	/**
	 * Returns value of "then" attribute.
	 * If then attribute is not set, iterates through child nodes and renders ThenViewHelper.
	 * If then attribute is not set and no ThenViewHelper is found, all child nodes are rendered
	 *
	 * @return string rendered ThenViewHelper or contents of <f:if> if no ThenViewHelper was found
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	protected function renderThenChild() {
		if ($this->arguments->hasArgument('then')) {
			return $this->arguments['then'];
		}
		foreach ($this->childNodes as $childNode) {
			if ($childNode instanceof \F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode
				&& $childNode->getViewHelperClassName() === 'F3\Fluid\ViewHelpers\ThenViewHelper') {
				$data = $childNode->evaluate($this->getRenderingContext());
				return $data;
			}
		}
		return $this->renderChildren();
	}

	/**
	 * Returns value of "else" attribute.
	 * If else attribute is not set, iterates through child nodes and renders ElseViewHelper.
	 * If else attribute is not set and no ElseViewHelper is found, an empty string will be returned.
	 *
	 * @return string rendered ElseViewHelper or an empty string if no ThenViewHelper was found
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	protected function renderElseChild() {
		foreach ($this->childNodes as $childNode) {
			if ($childNode instanceof \F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode
				&& $childNode->getViewHelperClassName() === 'F3\Fluid\ViewHelpers\ElseViewHelper') {
				return $childNode->evaluate($this->getRenderingContext());
			}
		}
		if ($this->arguments->hasArgument('else')) {
			return $this->arguments['else'];
		}
		return '';
	}
}

?>
<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\ViewHelpers;

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
 * This view helper implements an if/else condition.
 * @see F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode::convertArgumentValue() to find see how boolean arguments are evaluated
 *
 * = Conditions =
 *
 * As a condition is a boolean value, you can just use a boolean argument.
 * Alternatively, you can write a boolean expression there.
 * Boolean expressions have the following form:
 * XX Comparator YY
 * Comparator is one of: ==, !=, <, <=, >, >= and %
 * The % operator converts the result of the % operation to boolean.
 *
 * XX and YY can be one of:
 * - number
 * - Object Accessor
 * - Array
 * - a ViewHelper
 * Note: Strings at XX/YY are NOT allowed.
 *
 * <code title="condition example">
 * <f:if condition="{rank} > 100">
 *   Will be shown if rank is > 100
 * </f:if>
 * <f:if condition="{rank} % 2">
 *   Will be shown if rank % 2 != 0.
 * </f:if>
 * <f:if condition="{rank} == {k:bar()}">
 *   Checks if rank is equal to the result of the ViewHelper "k:bar"
 * </f:if>
 * </code>
 *
 * = Examples =
 *
 * <code title="Basic usage">
 * <f:if condition="somecondition">
 *   This is being shown in case the condition matches
 * </f:if>
 * </code>
 *
 * Everything inside the <f:if> tag is being displayed if the condition evaluates to TRUE.
 *
 * <code title="If / then / else">
 * <f:if condition="somecondition">
 *   <f:then>
 *     This is being shown in case the condition matches.
 *   </f:then>
 *   <f:else>
 *     This is being displayed in case the condition evaluates to FALSE.
 *   </f:else>
 * </f:if>
 * </code>
 *
 * Everything inside the "then" tag is displayed if the condition evaluates to TRUE.
 * Otherwise, everything inside the "else"-tag is displayed.
 *
 * <code title="inline notation">
 * {f:if(condition: someCondition, true: 'condition is met', false: 'condition is not met')}
 * </code>
 *
 * The value of the "then" attribute is displayed if the condition evaluates to TRUE.
 * Otherwise, everything the value of the "else"-attribute is displayed.
 *
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 * @scope prototype
 */
class IfViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper implements \F3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface {

	/**
	 * An array of \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode
	 * @var array
	 */
	protected $childNodes = array();

	/**
	 * @var F3\Fluid\Core\Rendering\RenderingContext
	 */
	protected $renderingContext;

	/**
	 * Setter for ChildNodes - as defined in ChildNodeAccessInterface
	 *
	 * @param array $childNodes Child nodes of this syntax tree node
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @api
	 */
	public function setChildNodes(array $childNodes) {
		$this->childNodes = $childNodes;
	}

	/**
	 * Sets the rendering context which needs to be passed on to child nodes
	 *
	 * @param F3\Fluid\Core\Rendering\RenderingContext $renderingContext the renderingcontext to use
	 */
	public function setRenderingContext(\F3\Fluid\Core\Rendering\RenderingContext $renderingContext) {
		$this->renderingContext = $renderingContext;
	}

	/**
	 * renders <f:then> child if $condition is true, otherwise renders <f:else> child.
	 *
	 * @param boolean $condition View helper condition
	 * @param string $then String to be returned if the condition is met
	 * @param string $else String to be returned if the condition is not met
	 * @return string the rendered string
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function render($condition, $then = NULL, $else = NULL) {
		if ($condition) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}

	/**
	 * Returns value of "then" attribute.
	 * If then attribute is not set, iterates through child nodes and renders ThenViewHelper.
	 * If then attribute is not set and no ThenViewHelper is found, all child nodes are rendered
	 *
	 * @return string rendered ThenViewHelper or contents of <f:if> if no ThenViewHelper was found
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function renderThenChild() {
		if ($this->arguments->hasArgument('then')) {
			return $this->arguments['then'];
		}
		foreach ($this->childNodes as $childNode) {
			if ($childNode instanceof \F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode
				&& $childNode->getViewHelperClassName() === 'F3\Fluid\ViewHelpers\ThenViewHelper') {
				$childNode->setRenderingContext($this->renderingContext);
				$data = $childNode->evaluate();
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
	 */
	protected function renderElseChild() {
		foreach ($this->childNodes as $childNode) {
			if ($childNode instanceof \F3\Fluid\Core\Parser\SyntaxTree\ViewHelperNode
				&& $childNode->getViewHelperClassName() === 'F3\Fluid\ViewHelpers\ElseViewHelper') {
				$childNode->setRenderingContext($this->renderingContext);
				return $childNode->evaluate();
			}
		}
		if ($this->arguments->hasArgument('else')) {
			return $this->arguments['else'];
		}
		return '';
	}
}

?>

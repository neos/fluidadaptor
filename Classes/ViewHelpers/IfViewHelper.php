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
 * @package Fluid
 * @subpackage ViewHelpers
 * @version $Id$
 */

/**
 * This view helper implements an if/else condition.
 * @see F3\Fluid\Core\Parser\SyntaxTree\convertArgumentValue() to find see how boolean arguments are evaluated
 *
 * = Examples =
 *
 * <code title="Basic usage">
 * <f:if condition="somecondition">
 *   This is being shown in case the condition matches
 * </f:if>
 * </code>
 *
 * Everything inside the <f3:if> tag is being displayed if the condition evaluates to TRUE.
 *
 * <code title="If / then / else">
 * <f3:if condition="somecondition">
 *   <f3:then>
 *     This is being shown in case the condition matches.
 *   </f3:then>
 *   <f3:else>
 *     This is being displayed in case the condition evaluates to FALSE.
 *   </f3:else>
 * </f3:if>
 * </code>
 *
 * Everything inside the "then" tag is displayed if the condition evaluates to TRUE.
 * Otherwise, everything inside the "else"-tag is displayed.
 *
 *
 *
 * @package Fluid
 * @subpackage ViewHelpers
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class IfViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper implements \F3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface {

	/**
	 * An array of \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode
	 * @var array
	 */
	protected $childNodes = array();

	/**
	 * @var F3\Fluid\Core\RenderingContext
	 */
	protected $renderingContext;

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
	 * Sets the rendering context which needs to be passed on to child nodes
	 *
	 * @param F3\Fluid\Core\RenderingContext $renderingContext the renderingcontext to use
	 * @internal
	 */
	public function setRenderingContext(\F3\Fluid\Core\RenderingContext $renderingContext) {
		$this->renderingContext = $renderingContext;
	}

	/**
	 * renders <f:then> child if $condition is true, otherwise renders <f:else> child.
	 *
	 * @param boolean $condition View helper condition
	 * @return string the rendered string
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function render($condition) {
		if ($condition) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}

	/**
	 * Iterates through child nodes and renders ThenViewHelper.
	 * If no ThenViewHelper is found, all child nodes are rendered
	 *
	 * @return string rendered ThenViewHelper or contents of <f:if> if no ThenViewHelper was found
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function renderThenChild() {
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
	 * Iterates through child nodes and renders ElseViewHelper.
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
		return '';
	}
}

?>

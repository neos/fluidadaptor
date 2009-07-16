<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Core\Parser\SyntaxTree;

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
 * Node which will call a ViewHelper associated with this node.
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 * @intenral
 */
class ViewHelperNode extends \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode {

	/**
	 * Namespace of view helper
	 * @var string
	 */
	protected $viewHelperClassName;

	/**
	 * Arguments of view helper - References to RootNodes.
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * List of comparators which are supported in the boolean expression language.
	 *
	 * Make sure that if one string is contained in one another, the longer string is listed BEFORE the shorter one.
	 * Example: put ">=" before ">"
	 * @var array of comparators
	 */
	static protected $comparators = array('==', '%', '>=', '>', '<=', '<');

	/**
	 * A regular expression which checks the text nodes of a boolean expression.
	 * Used to define how the regular expression language should look like.
	 * @var string Regular expression
	 */
	static protected $booleanExpressionTextNodeCheckerRegularExpression = '/
		^                 # Start with first input symbol
		(?:               # start repeat
			COMPARATORS   # We allow all comparators
			|\s*          # Arbitary spaces
			|[0-9]        # Numbers
			|\\.          # And the dot.
		)*
		$/x';

	/**
	 * Constructor.
	 *
	 * @param string $viewHelperClassName Fully qualified class name of the view helper
	 * @param array $arguments Arguments of view helper - each value is a RootNode.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function __construct($viewHelperClassName, array $arguments) {
		$this->viewHelperClassName = $viewHelperClassName;
		$this->arguments = $arguments;
	}

	/**
	 * Get class name of view helper
	 *
	 * @return string Class Name of associated view helper
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function getViewHelperClassName() {
		return $this->viewHelperClassName;
	}

	/**
	 * Call the view helper associated with this object.
	 *
	 * First, it evaluates the arguments of the view helper.
	 *
	 * If the view helper implements \F3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface,
	 * it calls setChildNodes(array childNodes) on the view helper.
	 *
	 * Afterwards, checks that the view helper did not leave a variable lying around.
	 *
	 * @return object evaluated node after the view helper has been called.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function evaluate() {
		if ($this->renderingContext === NULL) {
			throw new \F3\Fluid\Core\RuntimeException('RenderingContext is null in ViewHelperNode, but necessary. If this error appears, please report a bug!', 1242669031);
		}

		// Store if the ObjectAccessorPostProcessor has been enabled before this ViewHelper, because we need to re-enable it if needed after this ViewHelper
		$hasObjectAccessorPostProcessorBeenEnabledBeforeThisViewHelper = $this->renderingContext->isObjectAccessorPostProcessorEnabled();

		$objectFactory = $this->renderingContext->getObjectFactory();
		$viewHelper = $objectFactory->create($this->viewHelperClassName);
		$argumentDefinitions = $viewHelper->prepareArguments();

		$contextVariables = $this->renderingContext->getTemplateVariableContainer()->getAllIdentifiers();

		$evaluatedArguments = array();
		$renderMethodParameters = array();
		$this->renderingContext->setObjectAccessorPostProcessorEnabled(FALSE);
		if (count($argumentDefinitions)) {
			foreach ($argumentDefinitions as $argumentName => $argumentDefinition) {
				if (isset($this->arguments[$argumentName])) {
					$argumentValue = $this->arguments[$argumentName];
					$argumentValue->setRenderingContext($this->renderingContext);
					$evaluatedArguments[$argumentName] = $this->convertArgumentValue($argumentValue, $argumentDefinition->getType());
				} else {
					$evaluatedArguments[$argumentName] = $argumentDefinition->getDefaultValue();
				}
				if ($argumentDefinition->isMethodParameter()) {
					$renderMethodParameters[$argumentName] = $evaluatedArguments[$argumentName];
				}
			}
		}

		$viewHelperArguments = $objectFactory->create('F3\Fluid\Core\ViewHelper\Arguments', $evaluatedArguments);
		$viewHelper->setArguments($viewHelperArguments);
		$viewHelper->setTemplateVariableContainer($this->renderingContext->getTemplateVariableContainer());
		if ($this->renderingContext->getControllerContext() !== NULL) {
			$viewHelper->setControllerContext($this->renderingContext->getControllerContext());
		}
		$viewHelper->setViewHelperVariableContainer($this->renderingContext->getViewHelperVariableContainer());
		$viewHelper->setViewHelperNode($this);

		if ($viewHelper instanceof \F3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface) {
			$viewHelper->setChildNodes($this->childNodes);
			$viewHelper->setRenderingContext($this->renderingContext);
		}

		$viewHelper->validateArguments();
		$this->renderingContext->setObjectAccessorPostProcessorEnabled($viewHelper->isObjectAccessorPostProcessorEnabled());
		$viewHelper->initialize();
		try {
			$output = call_user_func_array(array($viewHelper, 'render'), $renderMethodParameters);
		} catch (\F3\Fluid\Core\ViewHelper\Exception $exception) {
			// @todo [BW] rethrow exception, log, ignore.. depending on the current context
			$output = $exception->getMessage();
		}

		$this->renderingContext->setObjectAccessorPostProcessorEnabled($hasObjectAccessorPostProcessorBeenEnabledBeforeThisViewHelper);

		if ($contextVariables != $this->renderingContext->getTemplateVariableContainer()->getAllIdentifiers()) {
			$endContextVariables = $this->renderingContext->getTemplateVariableContainer();
			$diff = array_intersect($endContextVariables, $contextVariables);

			throw new \F3\Fluid\Core\RuntimeException('The following context variable has been changed after the view helper "' . $this->viewHelperClassName . '" has been called: ' .implode(', ', $diff), 1236081302);
		}
		return $output;
	}

	/**
	 * Convert argument strings to their equivalents. Needed to handle strings with a boolean meaning.
	 *
	 * @param F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $syntaxTreeNode Value to be converted
	 * @param string $type Target type
	 * @return mixed New value
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function convertArgumentValue(\F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $syntaxTreeNode, $type) {
		if ($type === 'boolean') {
			return $this->evaluateBooleanExpression($syntaxTreeNode);
		}
		return $syntaxTreeNode->evaluate();
	}

	/**
	 * Convert boolean expression syntax tree to some meaningful value.
	 * The expression is available as the SyntaxTree of the argument.
	 *
	 * We currently only support expressions of the form:
	 * XX Comparator YY
	 * Where XX and YY can be either:
	 * - a number
	 * - an Object accessor
	 * - an array
	 * - a ViewHelper
	 *
	 * and comparator must be one of the above.
	 *
	 * In case no comparator is found, the fallback of "convertToBoolean" is used.
	 *
	 *
	 * Internal work:
	 * First, we loop through the child syntaxtree nodes, to fill the left side of the comparator,
	 * the right side of the comparator, and the comparator itself.
	 * Then, we evaluate the obtained left and right side using the given comparator. This is done inside the evaluateComparator method.
	 *
	 * @param F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $syntaxTreeNode Value to be converted
	 * @return boolean Evaluated value
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function evaluateBooleanExpression(\F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $syntaxTreeNode) {
		$childNodes = $syntaxTreeNode->getChildNodes();
		if (count($childNodes) > 3) {
			throw new \F3\Fluid\Core\RuntimeException('The expression "' . $syntaxTreeNode->evaluate() . '" has more than tree parts.', 1244201848);
		}

		$leftSide = NULL;
		$rightSide = NULL;
		$comparator = NULL;
		foreach ($childNodes as $childNode) {
			$childNode->setRenderingContext($this->renderingContext);

			if ($childNode instanceof \F3\Fluid\Core\Parser\SyntaxTree\TextNode && !preg_match(str_replace('COMPARATORS', implode('|', self::$comparators), self::$booleanExpressionTextNodeCheckerRegularExpression), $childNode->evaluate())) {
				$comparator = NULL;
				break; // skip loop and fall back to classical to boolean conversion.
			}

			if ($comparator !== NULL) {
				// comparator already set, we are evaluating the right side of the comparator
				if ($rightSide === NULL) {
					$rightSide = $childNode->evaluate();
				} else {
					$rightSide .= $childNode->evaluate();
				}
			} elseif ($childNode instanceof \F3\Fluid\Core\Parser\SyntaxTree\TextNode
				&& ($comparator = $this->getComparatorFromString($childNode->evaluate()))) {
				// comparator in current string segment
				$explodedString = explode($comparator, $childNode->evaluate());
				if (isset($explodedString[0]) && trim($explodedString[0]) !== '') {
					$leftSide .= trim($explodedString[0]);
				}
				if (isset($explodedString[1]) && trim($explodedString[1]) !== '') {
					$rightSide .= trim($explodedString[1]);
				}
			} else {
				// comparator not found yet, on the left side of the comparator
				if ($leftSide === NULL) {
					$leftSide = $childNode->evaluate();
				} else {
					$leftSide .= $childNode->evaluate();
				}
			}
		}

		if ($comparator !== NULL) {
			return $this->evaluateComparator($comparator, $leftSide, $rightSide);
		} else {
			$syntaxTreeNode->setRenderingContext($this->renderingContext);
			return $this->convertToBoolean($syntaxTreeNode->evaluate());
		}
	}

	/**
	 * Do the actual comparison. Compares $leftSide and $rightSide with $comparator and emits a boolean value
	 *
	 * @param string $comparator One of self::$comparators
	 * @param mixed $leftSide Left side to compare
	 * @param mixed $rightSide Right side to compare
	 * @return boolean TRUE if comparison of left and right side using the comparator emit TRUE, false otherwise
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function evaluateComparator($comparator, $leftSide, $rightSide) {
		switch ($comparator) {
			case '==':
				return ($leftSide == $rightSide);
				break;
			case '%':
				return (boolean)((int)$leftSide % (int)$rightSide);
			case '>':
				return ($leftSide > $rightSide);
			case '>=':
				return ($leftSide >= $rightSide);
			case '<':
				return ($leftSide < $rightSide);
			case '<=':
				return ($leftSide <= $rightSide);
			default:
				throw new \F3\Fluid\Core\RuntimeException('Comparator "' . $comparator . '" was not implemented. Please report a bug.', 1244234398);
		}
	}

	/**
	 * Determine if there is a comparator inside $string, and if yes, returns it.
	 *
	 * @param string $string string to check for a comparator inside
	 * @return string The comparator or NULL if none found.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function getComparatorFromString($string) {
		foreach (self::$comparators as $comparator) {
			if (strpos($string, $comparator) !== FALSE) {
				return $comparator;
			}
		}

		return NULL;
	}

	/**
	 * Convert argument strings to their equivalents. Needed to handle strings with a boolean meaning.
	 *
	 * @param mixed $value Value to be converted to boolean
	 * @return mixed New value
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @todo this should be moved to another class
	 */
	protected function convertToBoolean($value) {
		if (is_bool($value)) {
			return $value;
		}
		if (is_string($value)) {
			return (strtolower($value) !== 'false' && !empty($value));
		}
		if (is_numeric($value)) {
			return $value > 0;
		}
		if (is_array($value) || (is_object($value) && $value instanceof \Countable)) {
			return count($value) > 0;
		}
		if (is_object($value)) {
			return TRUE;
		}
		return FALSE;
	}
}

?>
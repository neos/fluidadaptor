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
 * Node which will call a ViewHelper associated with this node.
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class ViewHelperNode extends \TYPO3\Fluid\Core\Parser\SyntaxTree\AbstractNode {

	/**
	 * Class name of view helper
	 * @var string
	 */
	protected $viewHelperClassName;

	/**
	 * Arguments of view helper - References to RootNodes.
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * The ViewHelper associated with this node
	 * @var \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper
	 */
	protected $uninitializedViewHelper = NULL;

	/**
	 * A mapping RenderingContext -> ViewHelper to only re-initialize ViewHelpers
	 * when a context change occurs.
	 * @var \SplObjectStorage
	 */
	protected $viewHelpersByContext = NULL;



	/**
	 * Constructor.
	 *
	 * @param \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper $viewHelper The view helper
	 * @param array $arguments Arguments of view helper - each value is a RootNode.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function __construct(\TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper $viewHelper, array $arguments) {
		$this->uninitializedViewHelper = $viewHelper;
		$this->viewHelpersByContext = new \SplObjectStorage();
		$this->arguments = $arguments;
		$this->viewHelperClassName = get_class($this->uninitializedViewHelper);
	}

	/**
	 * Returns the attached (but still uninitialized) ViewHelper for this ViewHelperNode.
	 * We need this method because sometimes Interceptors need to ask some information from the ViewHelper.
	 *
	 * @return \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper the attached ViewHelper, if it is initialized
	 */
	public function getUninitializedViewHelper() {
		return $this->uninitializedViewHelper;
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
	 * INTERNAL - only needed for compiling templates
	 *
	 * @return array
	 * @internal
	 */
	public function getArguments() {
		return $this->arguments;
	}

	/**
	 * Call the view helper associated with this object.
	 *
	 * First, it evaluates the arguments of the view helper.
	 *
	 * If the view helper implements \TYPO3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface,
	 * it calls setChildNodes(array childNodes) on the view helper.
	 *
	 * Afterwards, checks that the view helper did not leave a variable lying around.
	 *
	 * @param \TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 * @return object evaluated node after the view helper has been called.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Karsten Dambekalns <karsten@typo3.org>
	 */
	public function evaluate(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
		$objectManager = $renderingContext->getObjectManager();
		$contextVariables = $renderingContext->getTemplateVariableContainer()->getAllIdentifiers();

		if ($this->viewHelpersByContext->contains($renderingContext)) {
			$viewHelper = $this->viewHelpersByContext[$renderingContext];
		} else {
			$viewHelper = clone $this->uninitializedViewHelper;
			$this->viewHelpersByContext->attach($renderingContext, $viewHelper);
		}

		$evaluatedArguments = array();
		if (count($viewHelper->prepareArguments())) {
 			foreach ($viewHelper->prepareArguments() as $argumentName => $argumentDefinition) {
				if (isset($this->arguments[$argumentName])) {
					$argumentValue = $this->arguments[$argumentName];
					$evaluatedArguments[$argumentName] = $argumentValue->evaluate($renderingContext);
				} else {
					$evaluatedArguments[$argumentName] = $argumentDefinition->getDefaultValue();
				}
			}
		}

		$viewHelper->setArguments($evaluatedArguments);
		$viewHelper->setViewHelperNode($this);
		$viewHelper->setRenderingContext($renderingContext);

		if ($viewHelper instanceof \TYPO3\Fluid\Core\ViewHelper\Facets\ChildNodeAccessInterface) {
			$viewHelper->setChildNodes($this->childNodes);
		}

		$output = $viewHelper->initializeArgumentsAndRender();

		return $output;
	}

	/**
	 * Clean up for serializing.
	 *
	 * @return array
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function __sleep() {
		return array('viewHelperClassName', 'arguments', 'childNodes');
	}
}

?>
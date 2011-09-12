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
 * A node which handles object access. This means it handles structures like {object.accessor.bla}
 *
 * @scope prototype
 */
class ObjectAccessorNode extends \TYPO3\Fluid\Core\Parser\SyntaxTree\AbstractNode {

	/**
	 * Object path which will be called. Is a list like "post.name.email"
	 * @var string
	 */
	protected $objectPath;

	/**
	 * Constructor. Takes an object path as input.
	 *
	 * The first part of the object path has to be a variable in the
	 * TemplateVariableContainer.
	 *
	 * @param string $objectPath An Object Path, like object1.object2.object3
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function __construct($objectPath) {
		$this->objectPath = $objectPath;
	}


	/**
	 * Internally used for building up cached templates; do not use directly!
	 *
	 * @return string
	 * @internal
	 */
	public function getObjectPath() {
		return $this->objectPath;
	}

	/**
	 * Evaluate this node and return the correct object.
	 *
	 * Handles each part (denoted by .) in $this->objectPath in the following order:
	 * - call appropriate getter
	 * - call public property, if exists
	 * - fail
	 *
	 * The first part of the object path has to be a variable in the
	 * TemplateVariableContainer.
	 *
	 * @param \TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 * @return object The evaluated object, can be any object type.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function evaluate(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
		return self::getPropertyPath($renderingContext->getTemplateVariableContainer(), $this->objectPath, $renderingContext);
	}

	/**
	 * Gets a property path from a given object or array.
	 *
	 * If propertyPath is "bla.blubb", then we first call getProperty($object, 'bla'),
	 * and on the resulting object we call getProperty(..., 'blubb').
	 *
	 * For arrays the keys are checked likewise.
	 *
	 * @param mixed $subject An object or array
	 * @param string $propertyPath
	 * @param \TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
	 * @return mixed Value of the property
	 */
	static public function getPropertyPath($subject, $propertyPath, \TYPO3\Fluid\Core\Rendering\RenderingContextInterface $renderingContext) {
		$propertyPathSegments = explode('.', $propertyPath);
		foreach ($propertyPathSegments as $pathSegment) {
			$propertyExists = FALSE;
			$propertyValue = \TYPO3\FLOW3\Reflection\ObjectAccess::getPropertyInternal($subject, $pathSegment, FALSE, $propertyExists);
			if ($propertyExists !== TRUE && (is_array($subject) || $subject instanceof \ArrayAccess) && isset($subject[$pathSegment])) {
				$subject = $subject[$pathSegment];
			} else {
				$subject = $propertyValue;
			}

			if ($subject instanceof \TYPO3\Fluid\Core\Parser\SyntaxTree\RenderingContextAwareInterface) {
				$subject->setRenderingContext($renderingContext);
			}
		}
		return $subject;
	}
}
?>
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
 * Cycle view helper. Iterates through the specified values.
 *
 * = Examples =
 *
 * <code title="Simple">
 * <f:for each="{0:1, 1:2, 2:3, 3:4}" as="foo"><f:cycle values="{0: 'foo', 1: 'bar', 2: 'baz'}" as="cycle">{cycle}</f:cycle></f:for>
 * </code>
 *
 * Output:
 * foobarbazfoo
 *
 * <code title="Alternating CSS class">
 * <ul>
 *   <f:for each="{0:1, 1:2, 2:3, 3:4}" as="foo">
 *     <f:cycle values="{0: 'odd', 1: 'even'}" as="zebraClass">
 *       <li class="{zebraClass}">{foo}</li>
 *     </f:cycle>
 *   </f:for>
 * </ul>
 * </code>
 *
 * Output:
 * <ul>
 *   <li class="odd">1</li>
 *   <li class="even">2</li>
 *   <li class="odd">3</li>
 *   <li class="even">4</li>
 * </ul>
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class CycleViewHelper extends \F3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var array|\SplObjectStorage the values to be iterated through
	 */
	protected $values = NULL;

	/**
	 * @var integer current values index
	 */
	protected $currentCycleIndex = NULL;

	/**
	 * @param array $values The array or \SplObjectStorage to iterated over
	 * @param string $as The name of the iteration variable
	 * @return string Rendered result
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function render($values, $as) {
		if ($values === NULL) {
			return $this->renderChildren();
		}
		if ($this->values === NULL) {
			$this->initializeValues($values);
		}
		if ($this->currentCycleIndex === NULL || $this->currentCycleIndex >= count($this->values)) {
			$this->currentCycleIndex = 0;
		}

		$currentValue = isset($this->values[$this->currentCycleIndex]) ? $this->values[$this->currentCycleIndex] : NULL;
		$this->templateVariableContainer->add($as, $currentValue);
		$output = $this->renderChildren();
		$this->templateVariableContainer->remove($as);

		$this->currentCycleIndex ++;

		return $output;
	}

	/**
	 * Sets this->values to the current values argument and resets $this->currentCycleIndex.
	 *
	 * @param array $values The array or \SplObjectStorage to be stored in $this->values
	 * @return void
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function initializeValues($values) {
		if (is_object($values)) {
			if (!$values instanceof \Traversable) {
				throw new \F3\Fluid\Core\ViewHelper\Exception('CycleViewHelper only supports arrays and objects implementing \Traversable interface' , 1248728393);
			}
			$this->values = $this->convertToArray($values);
		} else {
			$this->values = array_values($values);
		}
		$this->currentCycleIndex = 0;
	}

	/**
	 * Turns the given object into an array.
	 * The object has to implement the \Traversable interface
	 *
	 * @param \Traversable $object The object to be turned into an array. If the object implements \Iterator the key will NOT be preserved.
	 * @return array The resulting array
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	protected function convertToArray(\Traversable $object) {
		$array = array();
		foreach ($object as $singleElement) {
			$array[] = $singleElement;
		}
		return $array;
	}
}

?>

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
 * @package Fluid
 * @subpackage Core
 * @version $Id$
 */

/**
 * Arguments list. Wraps an array, but only allows read-only methods on it.
 * Is available inside every view helper as $this->arguments - and you use it as if it was an array.
 * However, you can only read, and not write to it.
 *
 * @package Fluid
 * @subpackage Core
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class Arguments implements \ArrayAccess {

	/**
	 * Array storing the arguments themselves
	 */
	protected $arguments = array();

	/**
	 * Constructor.
	 *
	 * @param array $arguments Array of arguments
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function __construct(array $arguments) {
		$this->arguments = $arguments;
	}

	/**
	 * Checks if a given key exists in the array
	 *
	 * @param string $key Key to check
	 * @return boolean true if exists
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function offsetExists($key) {
		return array_key_exists($key, $this->arguments);
	}

	/**
	 * Returns the value to the given key.
	 *
	 * @param  $key Key to get.
	 * @return object associated value
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function offsetGet($key) {
		if (!array_key_exists($key, $this->arguments)) {
			return NULL;
		}

		return $this->arguments[$key];
	}

	/**
	 * Throw exception if you try to set a value.
	 *
	 * @param string $name
	 * @param object $value
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function offsetSet($name, $value) {
		throw new \F3\Fluid\Core\RuntimeException('Tried to set argument "' . $name . '", but setting arguments is forbidden.', 1236080693);
	}

	/**
	 * Throw exception if you try to unset a value.
	 *
	 * @param string $name
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function offsetUnset($name) {
		throw new \F3\Fluid\Core\RuntimeException('Tried to unset argument "' . $name . '", but setting arguments is forbidden.', 1236080702);
	}

	/**
	 * Checks if an argument with the specified name exists
	 *
	 * @param string $argumentName Name of the argument to check for
	 * @return boolean TRUE if such an argument exists, otherwise FALSE
	 * @see offsetExists()
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @internal
	 */
	public function hasArgument($argumentName) {
		return $this->offsetExists($argumentName) && $this->arguments[$argumentName] !== NULL;
	}
}
?>
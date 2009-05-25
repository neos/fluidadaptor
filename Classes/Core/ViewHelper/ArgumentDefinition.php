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
 * Argument definition of each view helper argument
 *
 * @package Fluid
 * @subpackage Core
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @internal
 */
class ArgumentDefinition {

	/**
	 * Name of argument
	 * @var string
	 */
	protected $name;

	/**
	 * Type of argument
	 * @var string
	 */
	protected $type;

	/**
	 * Description of argument
	 * @var string
	 */
	protected $description;

	/**
	 * Is argument required?
	 * @var boolean
	 */
	protected $required = FALSE;

	/**
	 * Default value for argument
	 * @var mixed
	 */
	protected $defaultValue = NULL;

	/**
	 * TRUE if it is a method parameter
	 * @var boolean
	 */
	protected $isMethodParameter = FALSE;

	/**
	 * Constructor for this argument definition.
	 *
	 * @param string $name Name of argument
	 * @param string $type Type of argument
	 * @param string $description Description of argument
	 * @param boolean $required TRUE if argument is required
	 * @param mixed $defaultValue Default value
	 * @param boolean $isMethodParameter TRUE if this argument is a method parameter
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function __construct($name, $type, $description, $required, $defaultValue = NULL, $isMethodParameter = FALSE) {
		$this->name = $name;
		$this->type = $type;
		$this->description = $description;
		$this->required = $required;
		$this->defaultValue = $defaultValue;
		$this->isMethodParameter = $isMethodParameter;
	}

	/**
	 * Get the name of the argument
	 *
	 * @return string Name of argument
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get the type of the argument
	 *
	 * @return string Type of argument
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the description of the argument
	 *
	 * @return string Description of argument
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Get the optionality of the argument
	 *
	 * @return boolean TRUE if argument is optional
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function isRequired() {
		return $this->required;
	}

	/**
	 * Get the default value, if set
	 *
	 * @return mixed Default value
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function getDefaultValue() {
		return $this->defaultValue;
	}

	/**
	 * TRUE if it is a method parameter
	 *
	 * @return boolean TRUE if it's a method parameter
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function isMethodParameter() {
		return $this->isMethodParameter;
	}
}

?>
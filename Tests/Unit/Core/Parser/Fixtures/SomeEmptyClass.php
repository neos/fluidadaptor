<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Core\Parser\Fixtures;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * @version $Id$
 */
/**
 * Example class
 *
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @author Sebastian Kurfürst <sebastian@typo3.org>
 */
class SomeEmptyClass {
	public $publicVariable = "Hallo";
	protected $protectedVariable;
	
	public function __construct($protectedVariable) {
		$this->protectedVariable = $protectedVariable;
	}
	
	public function getSubproperty() {
		return $this->protectedVariable;
	}
}


?>

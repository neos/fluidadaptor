<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\ViewHelpers\Uri;

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
 * A view helper for creating URIs to external targets.
 * Currently the specified URI is simply passed through.
 *
 * = Examples =
 * 
 * <code>
 * <f:uri.external uri="http://www.typo3.org" />
 * </code>
 *
 * Output:
 * http://www.typo3.org
 *
 * @package Fluid
 * @subpackage ViewHelpers
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class ExternalViewHelper extends \F3\Fluid\Core\ViewHelper\TagBasedViewHelper {

	/**
	 * @param string $uri the target URI
	 * @return string rendered URI
	 * @author Bastian Waidelich <bastian@typo3.org>
	 * @api
	 */
	public function render($uri) {
		return $uri;
	}
}


?>

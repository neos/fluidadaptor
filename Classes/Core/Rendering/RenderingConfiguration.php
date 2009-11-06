<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Core\Rendering;

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
 * The rendering configuration. Contains all configuration needed to configure the rendering of a SyntaxTree.
 * This currently contains:
 * - the active ObjectAccessorPostProcessor, if any
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @scope prototype
 */
class RenderingConfiguration {

	/**
	 * Object accessor post processor to use
	 * @var F3\Fluid\Core\Rendering\ObjectAccessorPostProcessorInterface
	 */
	protected $objectAccessorPostProcessor;

	/**
	 * Set the Object accessor post processor
	 *
	 * @param F3\Fluid\Core\Rendering\ObjectAccessorPostProcessorInterface $objectAccessorPostProcessor The ObjectAccessorPostProcessor to set
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function setObjectAccessorPostProcessor(\F3\Fluid\Core\Rendering\ObjectAccessorPostProcessorInterface $objectAccessorPostProcessor) {
		$this->objectAccessorPostProcessor = $objectAccessorPostProcessor;
	}

	/**
	 * Get the currently set ObjectAccessorPostProcessor
	 *
	 * @return F3\Fluid\Core\Rendering\ObjectAccessorPostProcessorInterface The currently set ObjectAccessorPostProcessor, or NULL if none set.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function getObjectAccessorPostProcessor() {
		return $this->objectAccessorPostProcessor;
	}
}
?>
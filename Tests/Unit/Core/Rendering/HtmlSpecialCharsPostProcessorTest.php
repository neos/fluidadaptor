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
 * Testcase for HtmlSPecialChartPostProcessor
 *
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class HtmlSpecialCharsPostProcessorTest extends \F3\Testing\BaseTestCase {

	/**
	 * RenderingConfiguration
	 * @var \F3\Fluid\Core\Rendering\RenderingConfiguration
	 */
	protected $htmlSpecialCharsPostProcessor;

	public function setUp() {
		$this->htmlSpecialCharsPostProcessor = new \F3\Fluid\Core\Rendering\HtmlSpecialCharsPostProcessor();
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function postProcessorReturnsObjectsIfInArgumentsMode() {
		$string = 'Expected <p>';
		$this->assertEquals($string, $this->htmlSpecialCharsPostProcessor->process($string, FALSE));
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function postProcessorReturnsChangedObjectsIfInArgumentsMode() {
		$string = 'Expected <p>';
		$expected = 'Expected &lt;p&gt;';
		$this->assertEquals($expected, $this->htmlSpecialCharsPostProcessor->process($string, TRUE));
	}
}
?>
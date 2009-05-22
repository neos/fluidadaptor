<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Core\Rendering;

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
 * @package Fluid
 * @subpackage Tests
 * @version $Id: ParsingStateTest.php 2265 2009-05-19 18:52:02Z sebastian $
 */
/**
 * Testcase for HTMLSPecialChartPostProcessor
 *
 * @package Fluid
 * @subpackage Tests
 * @version $Id: ParsingStateTest.php 2265 2009-05-19 18:52:02Z sebastian $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class HTMLSpecialCharsPostProcessorTest extends \F3\Testing\BaseTestCase {

	/**
	 * RenderingConfiguration
	 * @var \F3\Fluid\Core\Rendering\RenderingConfiguration
	 */
	protected $htmlSpecialCharsPostProcessor;

	public function setUp() {
		$this->htmlSpecialCharsPostProcessor = new \F3\Fluid\Core\Rendering\HTMLSpecialCharsPostProcessor();
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function postProcessorReturnsObjectsIfInArgumentsMode() {
		$string = 'Expected <p>';
		$this->assertEquals($string, $this->htmlSpecialCharsPostProcessor->process($string, TRUE));
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function postProcessorReturnsChangedObjectsIfInArgumentsMode() {
		$string = 'Expected <p>';
		$expected = 'Expected &lt;p&gt;';
		$this->assertEquals($expected, $this->htmlSpecialCharsPostProcessor->process($string, FALSE));
	}
}
?>
<?php
namespace TYPO3\Fluid\Tests\Unit\Core\ViewHelper;

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
 * Testcase for TemplateVariableContainer
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class TemplateVariableContainerTest extends \TYPO3\FLOW3\Tests\UnitTestCase {

	/**
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function setUp() {
		$this->variableContainer = new \TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer();
	}

	/**
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function tearDown() {
		unset($this->variableContainer);
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function addedObjectsCanBeRetrievedAgain() {
		$object = "StringObject";
		$this->variableContainer->add("variable", $object);
		$this->assertSame($this->variableContainer->get('variable'), $object, 'The retrieved object from the context is not the same as the stored object.');
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function addedObjectsCanBeRetrievedAgainUsingArrayAccess() {
		$object = "StringObject";
		$this->variableContainer['variable'] = $object;
		$this->assertSame($this->variableContainer->get('variable'), $object);
		$this->assertSame($this->variableContainer['variable'], $object);
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function addedObjectsExistInArray() {
		$object = "StringObject";
		$this->variableContainer->add("variable", $object);
		$this->assertTrue($this->variableContainer->exists('variable'));
		$this->assertTrue(isset($this->variableContainer['variable']));
	}

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function addedObjectsExistInAllIdentifiers() {
		$object = "StringObject";
		$this->variableContainer->add("variable", $object);
		$this->assertEquals($this->variableContainer->getAllIdentifiers(), array('variable'), 'Added key is not visible in getAllIdentifiers');
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function duplicateIdentifiersThrowException() {
		$this->variableContainer->add('variable', 'string1');
		$this->variableContainer['variable'] = 'string2';
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @author Bastian Waidelich <bastian@typo3.org>
	 */
	public function addingReservedIdentifiersThrowException() {
		$this->variableContainer->add('TrUe', 'someValue');
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function gettingNonexistentValueThrowsException() {
		$this->variableContainer->get('nonexistent');
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function deletingNonexistentValueThrowsException() {
		$this->variableContainer->remove('nonexistent');
	}

	/**
	 * @test
	 * @expectedException \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function removeReallyRemovesVariables() {
		$this->variableContainer->add('variable', 'string1');
		$this->variableContainer->remove('variable');
		$this->variableContainer->get('variable');
	}

	/**
	 * @test
	 */
	public function whenVariablesAreEmpty_getAll_shouldReturnEmptyArray() {
		$this->assertSame(array(), $this->variableContainer->get('_all'));
	}

	/**
	 * @test
	 */
	public function getAllShouldReturnAllVariables() {
		$this->variableContainer->add('name', 'Simon');
		$this->assertSame(array('name' => 'Simon'), $this->variableContainer->get('_all'));
	}

	/**
	 * @test
	 * @expectedException TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 */
	public function addingVariableNamedAllShouldThrowException() {
		$this->variableContainer->add('_all', 'foo');
	}
}

?>
<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid;

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
 * @subpackage Test
 * @version $Id:$
 */
/**
 * Testcase for Regular expressions in parser
 *
 * @package
 * @subpackage Tests
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class Test extends \F3\Testing\BaseTestCase {

	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function testSCAN_PATTERN_NAMESPACEDECLARATION() {
		$pattern = \F3\Fluid\Core\TemplateParser::SCAN_PATTERN_NAMESPACEDECLARATION;
		$this->assertEquals(preg_match($pattern, '{namespace f3=F3\Bla\blubb}'), 1, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did not match a namespace declaration (1).');
		$this->assertEquals(preg_match($pattern, '{namespace f3=F3\Bla\Blubb }'), 1, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did not match a namespace declaration (2).');
		$this->assertEquals(preg_match($pattern, '{namespace f3 = F3\Bla3\Blubb }'), 1, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did not match a namespace declaration (3).');
		$this->assertEquals(preg_match($pattern, ' \{namespace f3 = F3\Bla3\Blubb }'), 0, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did match a namespace declaration even if it was escaped. (1)');
		$this->assertEquals(preg_match($pattern, '\{namespace f3 = F3\Bla3\Blubb }'), 0, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did match a namespace declaration even if it was escaped. (2)');
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function testSPLIT_PATTERN_DYNAMICTAGS() {
		$pattern = $this->insertNamespaceIntoRegularExpression(\F3\Fluid\Core\TemplateParser::SPLIT_PATTERN_TEMPLATE_DYNAMICTAGS, array('f3', 't3'));

		$source = '<html><head> <f3:a.testing /> <f3:blablubb> {testing}</f4:blz> </t3:hi.jo>';
		$expected = array('<html><head> ','<f3:a.testing />', ' ', '<f3:blablubb>', ' {testing}</f4:blz> ', '</t3:hi.jo>');
		$this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with simple tags.');

		$source = 'hi<f3:testing attribute="Hallo>{yep}" nested:attribute="jup" />ja';
		$expected = array('hi', '<f3:testing attribute="Hallo>{yep}" nested:attribute="jup" />', 'ja');
		$this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with  > inside an attribute.');
		
		$source = 'hi<f3:testing attribute="Hallo\"{yep}" nested:attribute="jup" />ja';
		$expected = array('hi', '<f3:testing attribute="Hallo\"{yep}" nested:attribute="jup" />', 'ja');
		$this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly if a " is inside a double-quoted string.');
		
		$source = 'hi<f3:testing attribute=\'Hallo>{yep}\' nested:attribute="jup" />ja';
		$expected = array('hi', '<f3:testing attribute=\'Hallo>{yep}\' nested:attribute="jup" />', 'ja');
		$this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with single quotes as attribute delimiters.');
		
		$source = 'hi<f3:testing attribute=\'Hallo\\\'{yep}\' nested:attribute="jup" />ja';
		$expected = array('hi', '<f3:testing attribute=\'Hallo\\\'{yep}\' nested:attribute="jup" />', 'ja');
		$this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly if \' is inside a single-quoted attribute.');
		
		$source = 'Hallo <f3:testing><![CDATA[<f3:notparsed>]]></f3:testing>';
		$expected = array('Hallo ', '<f3:testing>', '<![CDATA[<f3:notparsed>]]>', '</f3:testing>');
		$this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly if there is a CDATA section the parser should ignore.');
		
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function testSCAN_PATTERN_DYNAMICTAG() {
		$pattern = $this->insertNamespaceIntoRegularExpression(\F3\Fluid\Core\TemplateParser::SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG, array('f3'));
		$source = '<f3:crop attribute="Hallo">';
		$expected = array (
			0 => '<f3:crop attribute="Hallo">',
			'NamespaceIdentifier' => 'f3',
			1 => 'f3',
			'MethodIdentifier' => 'crop',
			2 => 'crop',
			'Attributes' => ' attribute="Hallo"',
			3 => ' attribute="Hallo"',
			'Selfclosing' => '',
			4 => ''
		);
		preg_match($pattern, $source, $matches);
		$this->assertEquals($expected, $matches, 'The SCAN_PATTERN_DYNAMICTAG does not match correctly.');
		
		$source = '<f3:base />';
		$expected = array (
			0 => '<f3:base />',
			'NamespaceIdentifier' => 'f3',
			1 => 'f3',
			'MethodIdentifier' => 'base',
			2 => 'base',
			'Attributes' => '',
			3 => '',
			'Selfclosing' => '/',
			4 => '/'
		);
		preg_match($pattern, $source, $matches);
		$this->assertEquals($expected, $matches, 'The SCAN_PATTERN_DYNAMICTAG does not match correctly when there is a space before the self-closing tag.');
		
		$source = '<f3:crop attribute="Ha\"llo"/>';
		$expected = array (
			0 => '<f3:crop attribute="Ha\"llo"/>',
			'NamespaceIdentifier' => 'f3',
			1 => 'f3',
			'MethodIdentifier' => 'crop',
			2 => 'crop',
			'Attributes' => ' attribute="Ha\"llo"',
			3 => ' attribute="Ha\"llo"',
			'Selfclosing' => '/',
			4 => '/'
		);
		preg_match($pattern, $source, $matches);
		$this->assertEquals($expected, $matches, 'The SCAN_PATTERN_DYNAMICTAG does not match correctly with self-closing tags.');
		
		$source = '<f3:link.uriTo complex:attribute="Ha>llo" a="b" c=\'d\'/>';
		$expected = array (
			0 => '<f3:link.uriTo complex:attribute="Ha>llo" a="b" c=\'d\'/>',
			'NamespaceIdentifier' => 'f3',
			1 => 'f3',
			'MethodIdentifier' => 'link.uriTo',
			2 => 'link.uriTo',
			'Attributes' => ' complex:attribute="Ha>llo" a="b" c=\'d\'',
			3 => ' complex:attribute="Ha>llo" a="b" c=\'d\'',
			'Selfclosing' => '/',
			4 => '/'
		);
		preg_match($pattern, $source, $matches);
		$this->assertEquals($expected, $matches, 'The SCAN_PATTERN_DYNAMICTAG does not match correctly with complex attributes and > inside the attributes.');
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function testSCAN_PATTERN_CLOSINGDYNAMICTAG() {
		$pattern = $this->insertNamespaceIntoRegularExpression(\F3\Fluid\Core\TemplateParser::SCAN_PATTERN_TEMPLATE_CLOSINGVIEWHELPERTAG, array('f3'));
		$this->assertEquals(preg_match($pattern, '</f3:bla>'), 1, 'The SCAN_PATTERN_CLOSINGDYNAMICTAG does not match a tag it should match.');
		$this->assertEquals(preg_match($pattern, '</f3:bla.a    >'), 1, 'The SCAN_PATTERN_CLOSINGDYNAMICTAG does not match a tag (with spaces included) it should match.');
		$this->assertEquals(preg_match($pattern, '</t3:bla>'), 0, 'The SCAN_PATTERN_CLOSINGDYNAMICTAG does match match a tag it should not match.');
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function testSPLIT_PATTERN_TAGARGUMENTS() {
		$pattern = \F3\Fluid\Core\TemplateParser::SPLIT_PATTERN_TAGARGUMENTS;
		$source = ' test="Hallo" argument:post="\'Web" other=\'Single"Quoted\'';
		$this->assertEquals(preg_match_all($pattern, $source, $matches, PREG_SET_ORDER), 3, 'The SPLIT_PATTERN_TAGARGUMENTS does not match correctly.');
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @todo finish unit test; add complete shorthand syntax!!
	 */
	public function testSPLIT_PATTERN_SHORTHANDSYNTAX() {
		$pattern = \F3\Fluid\Core\TemplateParser::SPLIT_PATTERN_SHORTHANDSYNTAX;
		
		$source = 'some string{Object.bla}here as well';
		$expected = array('some string', '{Object.bla}','here as well');
		$this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with a simple example.');
		
		$source = 'some {}string\{Object.bla}here as well';
		$expected = array('some {}string', '\{Object.bla}','here as well');
		$this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with an escaped example.');
		
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function testSCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS() {
		$pattern = \F3\Fluid\Core\TemplateParser::SCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS;
		$this->assertEquals(preg_match($pattern, '{object}'), 1, 'Object accessor not identified!');
		$this->assertEquals(preg_match($pattern, '{oBject1}'), 1, 'Object accessor not identified if there is a number and capitals inside!');
		$this->assertEquals(preg_match($pattern, '{object.recursive}'), 1, 'Object accessor not identified if there is a dot inside!');
		$this->assertEquals(preg_match($pattern, '{object-with-dash.recursive_value}'), 1, 'Object accessor not identified if there is a _ or - inside!');
		$this->assertEquals(preg_match($pattern, '\{object}'), 0, 'Object accessor identified, but it was escaped!');
		$this->assertEquals(preg_match($pattern, '{dash:value}'), 0, 'Object accessor identified, but was array!');
		$this->assertEquals(preg_match($pattern, '{}'), 0, 'Object accessor identified, but it was empty!');
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function testSCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS() {
		$pattern = \F3\Fluid\Core\TemplateParser::SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS;
		$this->assertEquals(preg_match($pattern, '{a:b}'), 1, 'Array syntax not identified!');
		$this->assertEquals(preg_match($pattern, '\{a:b}'), 0, 'Escaped Array syntax identified!');
		$this->assertEquals(preg_match($pattern, '{a:b, c :   d}'), 1, 'Array syntax not identified in case there are multiple properties!');
		$this->assertEquals(preg_match($pattern, '{a : 123}'), 1, 'Array syntax not identified when a number is passed as argument!');
		$this->assertEquals(preg_match($pattern, '{a:"String"}'), 1, 'Array syntax not identified in case of a double quoted string!');
		$this->assertEquals(preg_match($pattern, '{a:\'String\'}'), 1, 'Array syntax not identified in case of a single quoted string!');
		
		$expected = '{a:{bla:{x:z}, b: a}}';
		preg_match($pattern, $expected, $match);
		$this->assertEquals($match[0], $expected, 'If nested arrays appear, the string is not parsed correctly.');
		
		$expected = '{a:"{bla{{}"}';
		preg_match($pattern, $expected, $match);
		$this->assertEquals($match[0], $expected, 'If nested strings with {} inside appear, the string is not parsed correctly.');
	}
	
	/**
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function testSPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS() {
		$pattern = \F3\Fluid\Core\TemplateParser::SPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS;
		
		$source = '{a: b, e: {c:d, e:f}}';
		preg_match_all($pattern, $source, $matches, PREG_SET_ORDER);
		
		$expected = array(
			0 => array(
				0 => 'a: b',
				'ArrayPart' => 'a: b',
				1 => 'a: b',
				'Key' => 'a',
      			2 => 'a',
      			'DoubleQuotedString' => '',
      			3 => '',
				'SingleQuotedString' => '',
      			4 => '',
      			'VariableIdentifier' => 'b',
      			5 => 'b'
			),
			1 => array(
				0 => 'e: {c:d, e:f}',
				'ArrayPart' => 'e: {c:d, e:f}',
				1 => 'e: {c:d, e:f}',
				'Key' => 'e',
      			2 => 'e',
      			'DoubleQuotedString' => '',
      			3 => '',
				'SingleQuotedString' => '',
      			4 => '',
      			'VariableIdentifier' => '',
      			5 => '',
      			'Number' => '',
      			6 => '',
      			'Subarray' => 'c:d, e:f',
      			7 => 'c:d, e:f'
			)
		);
		$this->assertEquals($matches, $expected, 'The regular expression splitting the array apart does not work!');
	}
	
	/**
	 * Test the SCAN_PATTERN_CDATA which should detect <![CDATA[...]]> (with no leading or trailing spaces!)
	 * 
	 * @test
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function testSCAN_PATTERN_CDATA() {
		$pattern = \F3\Fluid\Core\TemplateParser::SCAN_PATTERN_CDATA;
		$this->assertEquals(preg_match($pattern, '<!-- Test -->'), 0, 'The SCAN_PATTERN_CDATA matches a comment, but it should not.');
		$this->assertEquals(preg_match($pattern, '<![CDATA[This is some ]]>'), 1, 'The SCAN_PATTERN_CDATA does not match a simple CDATA string.');
		$this->assertEquals(preg_match($pattern, '<![CDATA[This is<bla:test> some ]]>'), 1, 'The SCAN_PATTERN_CDATA does not match a CDATA string with tags inside..');
	}
	
	/**
	 * Helper method which replaces NAMESPACE in the regular expression with the real namespace used.
	 * 
	 * @param string $regularExpression The regular expression in which to replace NAMESPACE
	 * @param array $namespace List of namespace identifiers.
	 * @return string working regular expression with NAMESPACE replaced.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function insertNamespaceIntoRegularExpression($regularExpression, $namespace) {
		return str_replace('NAMESPACE', implode('|', $namespace), $regularExpression);
	}
}



?>

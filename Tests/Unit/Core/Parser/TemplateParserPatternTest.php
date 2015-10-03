<?php
namespace TYPO3\Fluid\Tests\Unit\Core\Parser;

/*
 * This file is part of the TYPO3.Fluid package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */
use TYPO3\Fluid\Core\Parser\TemplateParser;

/**
 * Testcase for Regular expressions in parser
 */
class TemplateParserPatternTest extends \TYPO3\Flow\Tests\UnitTestCase
{
    /**
     * @test
     */
    public function testSCAN_PATTERN_NAMESPACEDECLARATION()
    {
        $this->assertEquals(preg_match(TemplateParser::$SCAN_PATTERN_NAMESPACEDECLARATION, '{namespace acme=Acme.MyPackage\Bla\blubb}'), 1, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did not match a namespace declaration (1).');
        $this->assertEquals(preg_match(TemplateParser::$SCAN_PATTERN_NAMESPACEDECLARATION, '{namespace acme=Acme.MyPackage\Bla\Blubb }'), 1, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did not match a namespace declaration (2).');
        $this->assertEquals(preg_match(TemplateParser::$SCAN_PATTERN_NAMESPACEDECLARATION, '    {namespace foo = Foo\Bla3\Blubb }    '), 1, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did not match a namespace declaration (3).');
        $this->assertEquals(preg_match(TemplateParser::$SCAN_PATTERN_NAMESPACEDECLARATION, '    {namespace foo.bar  = Foo\Bla3\Blubb }    '), 1, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did not match a namespace declaration (4).');
        $this->assertEquals(preg_match(TemplateParser::$SCAN_PATTERN_NAMESPACEDECLARATION, ' \{namespace fblubb = TYPO3.Fluid\Bla3\Blubb }'), 0, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did match a namespace declaration even if it was escaped. (1)');
        $this->assertEquals(preg_match(TemplateParser::$SCAN_PATTERN_NAMESPACEDECLARATION, '\{namespace typo3 = TYPO3.TYPO3\Bla3\Blubb }'), 0, 'The SCAN_PATTERN_NAMESPACEDECLARATION pattern did match a namespace declaration even if it was escaped. (2)');
    }

    /**
     * @test
     */
    public function testSPLIT_PATTERN_DYNAMICTAGS()
    {
        $pattern = $this->insertNamespaceIntoRegularExpression(TemplateParser::$SPLIT_PATTERN_TEMPLATE_DYNAMICTAGS, array('typo3', 't3', 'f'));

        $source = '<html><head> <f:a.testing /> <f:blablubb> {testing}</f4:blz> </t3:hi.jo>';
        $expected = array('<html><head> ','<f:a.testing />', ' ', '<f:blablubb>', ' {testing}', '</f4:blz>', ' ', '</t3:hi.jo>');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with simple tags.');

        $source = 'hi<f:testing attribute="Hallo>{yep}" nested:attribute="jup" />ja';
        $expected = array('hi', '<f:testing attribute="Hallo>{yep}" nested:attribute="jup" />', 'ja');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with  > inside an attribute.');

        $source = 'hi<f:testing attribute="Hallo\"{yep}" nested:attribute="jup" />ja';
        $expected = array('hi', '<f:testing attribute="Hallo\"{yep}" nested:attribute="jup" />', 'ja');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly if a " is inside a double-quoted string.');

        $source = 'hi<f:testing attribute=\'Hallo>{yep}\' nested:attribute="jup" />ja';
        $expected = array('hi', '<f:testing attribute=\'Hallo>{yep}\' nested:attribute="jup" />', 'ja');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with single quotes as attribute delimiters.');

        $source = 'hi<f:testing attribute=\'Hallo\\\'{yep}\' nested:attribute="jup" />ja';
        $expected = array('hi', '<f:testing attribute=\'Hallo\\\'{yep}\' nested:attribute="jup" />', 'ja');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly if \' is inside a single-quoted attribute.');

        $source = 'Hallo <f:testing><![CDATA[<f:notparsed>]]></f:testing>';
        $expected = array('Hallo ', '<f:testing>', '<![CDATA[<f:notparsed>]]>', '</f:testing>');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly if there is a CDATA section the parser should ignore.');

        $veryLongViewHelper = '<f:form enctype="multipart/form-data" onsubmit="void(0)" onreset="void(0)" action="someAction" arguments="{arg1: \'val1\', arg2: \'val2\'}" controller="someController" package="YourCompanyName.somePackage" subpackage="YourCompanyName.someSubpackage" section="someSection" format="txt" additionalParams="{param1: \'val1\', param2: \'val2\'}" absolute="true" addQueryString="true" argumentsToBeExcludedFromQueryString="{0: \'foo\'}" />';
        $source = $veryLongViewHelper . 'Begin' . $veryLongViewHelper . 'End';
        $expected = array($veryLongViewHelper, 'Begin', $veryLongViewHelper, 'End');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly if the VH has lots of arguments.');

        $source = '<f:a.testing data-bar="foo"> <f:a.testing>';
        $expected = array('<f:a.testing data-bar="foo">', ' ', '<f:a.testing>');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with data- attribute.');

        $source = '<foo:a.testing someArgument="bar"> <f:a.testing>';
        $expected = array('<foo:a.testing someArgument="bar">', ' ', '<f:a.testing>');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with custom namespace identifier.');

        $source = '<foo.bar:a.testing someArgument="baz"> <f:a.testing>';
        $expected = array('<foo.bar:a.testing someArgument="baz">', ' ', '<f:a.testing>');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with custom multi-part namespace identifier.');

        $source = '<mixed.Case:viewHelper someArgument="baz">static<UPPERCASE:VIEWHELPER />static<UpperCamel:Case />';
        $expected = array('<mixed.Case:viewHelper someArgument="baz">', 'static', '<UPPERCASE:VIEWHELPER />', 'static', '<UpperCamel:Case />');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_DYNAMICTAGS pattern did not split the input string correctly with mixed case namespace identifier.');
    }

    /**
     * @test
     */
    public function testSCAN_PATTERN_DYNAMICTAG()
    {
        $pattern = $this->insertNamespaceIntoRegularExpression(TemplateParser::$SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG, array('f'));
        $source = '<f:crop attribute="Hallo">';
        $expected = array(
            0 => '<f:crop attribute="Hallo">',
            'NamespaceIdentifier' => 'f',
            1 => 'f',
            'MethodIdentifier' => 'crop',
            2 => 'crop',
            'Attributes' => ' attribute="Hallo"',
            3 => ' attribute="Hallo"',
            'Selfclosing' => '',
            4 => ''
        );
        preg_match($pattern, $source, $matches);
        $this->assertEquals($expected, $matches, 'The SCAN_PATTERN_DYNAMICTAG does not match correctly.');

        $pattern = $this->insertNamespaceIntoRegularExpression(TemplateParser::$SCAN_PATTERN_TEMPLATE_VIEWHELPERTAG, array('f'));
        $source = '<f:crop data-attribute="Hallo">';
        $expected = array(
            0 => '<f:crop data-attribute="Hallo">',
            'NamespaceIdentifier' => 'f',
            1 => 'f',
            'MethodIdentifier' => 'crop',
            2 => 'crop',
            'Attributes' => ' data-attribute="Hallo"',
            3 => ' data-attribute="Hallo"',
            'Selfclosing' => '',
            4 => ''
        );
        preg_match($pattern, $source, $matches);
        $this->assertEquals($expected, $matches, 'The SCAN_PATTERN_DYNAMICTAG does not match correctly with data- attributes.');

        $source = '<f:base />';
        $expected = array(
            0 => '<f:base />',
            'NamespaceIdentifier' => 'f',
            1 => 'f',
            'MethodIdentifier' => 'base',
            2 => 'base',
            'Attributes' => '',
            3 => '',
            'Selfclosing' => '/',
            4 => '/'
        );
        preg_match($pattern, $source, $matches);
        $this->assertEquals($expected, $matches, 'The SCAN_PATTERN_DYNAMICTAG does not match correctly when there is a space before the self-closing tag.');

        $source = '<f:crop attribute="Ha\"llo"/>';
        $expected = array(
            0 => '<f:crop attribute="Ha\"llo"/>',
            'NamespaceIdentifier' => 'f',
            1 => 'f',
            'MethodIdentifier' => 'crop',
            2 => 'crop',
            'Attributes' => ' attribute="Ha\"llo"',
            3 => ' attribute="Ha\"llo"',
            'Selfclosing' => '/',
            4 => '/'
        );
        preg_match($pattern, $source, $matches);
        $this->assertEquals($expected, $matches, 'The SCAN_PATTERN_DYNAMICTAG does not match correctly with self-closing tags.');

        $source = '<f:link.uriTo complex:attribute="Ha>llo" a="b" c=\'d\'/>';
        $expected = array(
            0 => '<f:link.uriTo complex:attribute="Ha>llo" a="b" c=\'d\'/>',
            'NamespaceIdentifier' => 'f',
            1 => 'f',
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
     */
    public function testSCAN_PATTERN_CLOSINGDYNAMICTAG()
    {
        $pattern = $this->insertNamespaceIntoRegularExpression(TemplateParser::$SCAN_PATTERN_TEMPLATE_CLOSINGVIEWHELPERTAG, array('f'));
        $this->assertEquals(preg_match($pattern, '</f:bla>'), 1, 'The SCAN_PATTERN_CLOSINGDYNAMICTAG does not match a tag it should match.');
        $this->assertEquals(preg_match($pattern, '</f:bla.a    >'), 1, 'The SCAN_PATTERN_CLOSINGDYNAMICTAG does not match a tag (with spaces included) it should match.');
        $this->assertEquals(preg_match($pattern, '</t:bla>'), 1, 'The SCAN_PATTERN_CLOSINGDYNAMICTAG does not match a unknown namespace tag it should match.');
    }

    /**
     * @test
     */
    public function testSPLIT_PATTERN_TAGARGUMENTS()
    {
        $pattern = TemplateParser::$SPLIT_PATTERN_TAGARGUMENTS;
        $source = ' test="Hallo" argument:post="\'Web" other=\'Single"Quoted\' data-foo="bar"';
        $this->assertEquals(preg_match_all($pattern, $source, $matches, PREG_SET_ORDER), 4, 'The SPLIT_PATTERN_TAGARGUMENTS does not match correctly.');
        $this->assertEquals('data-foo', $matches[3]['Argument']);
    }

    /**
     * @test
     */
    public function testSPLIT_PATTERN_SHORTHANDSYNTAX()
    {
        $pattern = $this->insertNamespaceIntoRegularExpression(TemplateParser::$SPLIT_PATTERN_SHORTHANDSYNTAX, array('f'));

        $source = 'some string{Object.bla}here as well';
        $expected = array('some string', '{Object.bla}', 'here as well');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with a simple example.');

        $source = 'some {}string\{Object.bla}here as well';
        $expected = array('some {}string\\', '{Object.bla}', 'here as well');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with an escaped example. (1)');

        $source = 'some {f:viewHelper()} as well';
        $expected = array('some ', '{f:viewHelper()}', ' as well');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with an escaped example. (2)');

        $source = 'abc {f:for(arg1: post)} def';
        $expected = array('abc ', '{f:for(arg1: post)}', ' def');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with an escaped example.(3)');

        $source = 'abc {bla.blubb->f:for(param:42)} def';
        $expected = array('abc ', '{bla.blubb->f:for(param:42)}', ' def');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with an escaped example.(4)');

        $source = 'abc {f:for(bla:"post{{")} def';
        $expected = array('abc ', '{f:for(bla:"post{{")}', ' def');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with an escaped example.(5)');

        $source = 'abc {f:for(param:"abc\"abc")} def';
        $expected = array('abc ', '{f:for(param:"abc\"abc")}', ' def');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with an escaped example.(6)');

        $source = 'abc {foo:for(arg1: post)} def';
        $expected = array('abc ', '{foo:for(arg1: post)}', ' def');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with a custom namespace identifier.(7)');

        $source = 'abc {bla.blubb->foo:for(param:42)} def';
        $expected = array('abc ', '{bla.blubb->foo:for(param:42)}', ' def');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with a custom namespace identifier.(8)');

        $source = 'abc {foo.bar:for(arg1: post)} def';
        $expected = array('abc ', '{foo.bar:for(arg1: post)}', ' def');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with a custom multi-part namespace identifier.(9)');

        $source = 'abc {bla.blubb->foo.bar:for(param:42)} def';
        $expected = array('abc ', '{bla.blubb->foo.bar:for(param:42)}', ' def');
        $this->assertEquals(preg_split($pattern, $source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY), $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX pattern did not split the input string correctly with a custom multi-part namespace identifier.(10)');
    }

    /**
     * @test
     */
    public function testSPLIT_PATTERN_SHORTHANDSYNTAX_VIEWHELPER()
    {
        $pattern = TemplateParser::$SPLIT_PATTERN_SHORTHANDSYNTAX_VIEWHELPER;

        $source = 'f:for(each: bla)';
        $expected = array(
            0 => array(
                0 => 'f:for(each: bla)',
                1 => 'f',
                'NamespaceIdentifier' => 'f',
                2 => 'for',
                'MethodIdentifier' => 'for',
                3 => 'each: bla',
                'ViewHelperArguments' => 'each: bla'
            )
        );
        preg_match_all($pattern, $source, $matches, PREG_SET_ORDER);
        $this->assertEquals($matches, $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX_VIEWHELPER');

        $source = 'f:for(each: bla)->foo.bar:bla(a:"b\"->(f:a()", cd: {a:b})';
        $expected = array(
            0 => array(
                0 => 'f:for(each: bla)',
                1 => 'f',
                'NamespaceIdentifier' => 'f',
                2 => 'for',
                'MethodIdentifier' => 'for',
                3 => 'each: bla',
                'ViewHelperArguments' => 'each: bla'
            ),
            1 => array(
                0 => 'foo.bar:bla(a:"b\"->(f:a()", cd: {a:b})',
                1 => 'foo.bar',
                'NamespaceIdentifier' => 'foo.bar',
                2 => 'bla',
                'MethodIdentifier' => 'bla',
                3 => 'a:"b\"->(f:a()", cd: {a:b}',
                'ViewHelperArguments' => 'a:"b\"->(f:a()", cd: {a:b}'
            )
        );
        preg_match_all($pattern, $source, $matches, PREG_SET_ORDER);
        $this->assertEquals($matches, $expected, 'The SPLIT_PATTERN_SHORTHANDSYNTAX_VIEWHELPER');
    }

    /**
     * @test
     */
    public function testSCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS()
    {
        $pattern = TemplateParser::$SCAN_PATTERN_SHORTHANDSYNTAX_OBJECTACCESSORS;
        $this->assertEquals(preg_match($pattern, '{object}'), 1, 'Object accessor not identified!');
        $this->assertEquals(preg_match($pattern, '{oBject1}'), 1, 'Object accessor not identified if there is a number and capitals inside!');
        $this->assertEquals(preg_match($pattern, '{object.recursive}'), 1, 'Object accessor not identified if there is a dot inside!');
        $this->assertEquals(preg_match($pattern, '{object-with-dash.recursive_value}'), 1, 'Object accessor not identified if there is a _ or - inside!');
        $this->assertEquals(preg_match($pattern, '{f:for()}'), 1, 'Object accessor not identified if it contains only of a ViewHelper.');
        $this->assertEquals(preg_match($pattern, '{f:for()->f:for2()}'), 1, 'Object accessor not identified if it contains only of a ViewHelper (nested).');
        $this->assertEquals(preg_match($pattern, '{abc->f:for()}'), 1, 'Object accessor not identified if there is a ViewHelper inside!');
        $this->assertEquals(preg_match($pattern, '{bla-blubb.recursive_value->f:for()->f:for()}'), 1, 'Object accessor not identified if there is a recursive ViewHelper inside!');
        $this->assertEquals(preg_match($pattern, '{f:for(arg1:arg1value, arg2: "bla\"blubb")}'), 1, 'Object accessor not identified if there is an argument inside!');
        $this->assertEquals(preg_match($pattern, '{foo.bar:for(arg1:arg1value)}'), 1, 'Object accessor not identified multi-part namespace identifier!');
        $this->assertEquals(preg_match($pattern, '{dash:value}'), 0, 'Object accessor identified, but was array!');
        // $this->assertEquals(preg_match($pattern, '{}'), 0, 'Object accessor identified, and it was empty!');
    }

    /**
     * @return array
     */
    public function dataProviderSCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS()
    {
        return array(
            array('string' => '{a:b}'),
            array('string' => '{a:b, c :   d}'),
            array('string' => '{  a:b, c :   d }'),

            // multiple lines
            array('string' => '{' . chr(10) . ' foo: 1,' . chr(10) . ' bar: 2' . chr(10) . '}'),

            // trailing comma
            array('string' => '{a:b, c :   d,}'),
            #array('string' => '{' . chr(10) . ' foo: 1,' . chr(10) . ' bar: 2,' . chr(10) . '}'),

            array('string' => '{a : 123}'),
            array('string' => '{a:"String"}'),
            array('string' => '{a:\'String\'}'),

            // empty string value
            array('string' => '{a:""}'),
            array('string' => '{a:\'\'}'),

            // nested arrays
            array('string' => '{a:{bla:{x:z}, b: a}}'),
            array('string' => '{a:"{bla{{}"}'),

            // quoted keys
            array('string' => '{"foo": "bar"}'),
            array('string' => '{"foo[bar]": "baz"}'),
            array('string' => '{"foo.bar": "baz"}'),
            array('string' => '{\'foo\': "bar"}'),
            array('string' => '{  \'foo\'  : "bar" }'),
            array('string' => '{"a":{bla:{x:z}, b: a}}'),
            array('string' => '{a:{bla:{"x":z}, b: a}}'),
            array('string' => '{"@a": "bar"}'),
            array('string' => '{\'_b\': "bar"}')
        );
    }

    /**
     * @param string $string
     * @dataProvider dataProviderSCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS()
     * @test
     */
    public function testSCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS($string)
    {
        $success = preg_match(TemplateParser::$SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS, $string, $matches) === 1;
        $this->assertTrue($success);
        $this->assertSame($string, $matches[0]);
    }

    /**
     * @return array
     */
    public function dataProviderInvalidSCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS()
    {
        return array(
            array('string' => '{"foo\': "bar"}'),
            array('string' => '{"": "bar"}'),
            array('string' => '{\'\': "bar"}'),
        );
    }

    /**
     * @param string $string
     * @dataProvider dataProviderInvalidSCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS()
     * @test
     */
    public function SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS_doesNotMatchInvalidSyntax($string)
    {
        $success = preg_match(TemplateParser::$SCAN_PATTERN_SHORTHANDSYNTAX_ARRAYS, $string, $matches) === 1;
        $this->assertFalse($success);
    }

    /**
     * @test
     */
    public function testSPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS()
    {
        $pattern = TemplateParser::$SPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS;

        $source = '{a: b, e: {c:d, "e#":f, \'g\': "h"}}';
        $success = preg_match_all($pattern, $source, $matches, PREG_SET_ORDER) > 0;

        $expected = array(
            0 => array(
                0 => 'a: b',
                'ArrayPart' => 'a: b',
                1 => 'a: b',
                'Key' => 'a',
                2 => 'a',
                'QuotedString' => '',
                3 => '',
                'VariableIdentifier' => 'b',
                4 => 'b'
            ),
            1 => array(
                0 => 'e: {c:d, "e#":f, \'g\': "h"}',
                'ArrayPart' => 'e: {c:d, "e#":f, \'g\': "h"}',
                1 => 'e: {c:d, "e#":f, \'g\': "h"}',
                'Key' => 'e',
                2 => 'e',
                'QuotedString' => '',
                3 => '',
                'VariableIdentifier' => '',
                4 => '',
                'Number' => '',
                5 => '',
                'Subarray' => 'c:d, "e#":f, \'g\': "h"',
                6 => 'c:d, "e#":f, \'g\': "h"'
            )
        );
        $this->assertTrue($success);
        $this->assertEquals($expected, $matches, 'The regular expression splitting the array apart does not work!');
    }

    /**
     * @test
     */
    public function SPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS_matchesQuotedKeys()
    {
        $pattern = TemplateParser::$SPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS;

        $source = '{"a": b, \'c\': d}';
        $success = preg_match_all($pattern, $source, $matches, PREG_SET_ORDER) > 0;

        $expected = array(
            0 => array(
                0 => '"a": b',
                'ArrayPart' => '"a": b',
                1 => '"a": b',
                'Key' => '"a"',
                2 => '"a"',
                'QuotedString' => '',
                3 => '',
                'VariableIdentifier' => 'b',
                4 => 'b'
            ),
            1 => array(
                0 => '\'c\': d',
                'ArrayPart' => '\'c\': d',
                1 => '\'c\': d',
                'Key' => '\'c\'',
                2 => '\'c\'',
                'QuotedString' => '',
                3 => '',
                'VariableIdentifier' => 'd',
                4 => 'd'
            )
        );
        $this->assertTrue($success);
        $this->assertEquals($expected, $matches, 'The regular expression splitting the array apart does not work!');
    }

    /**
     * @return array
     */
    public function dataProviderInvalidSPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS()
    {
        return array(
            array('string' => '{"a\': b}'),
            array('string' => '{"": "bar"}'),
            array('string' => '{\'\': "bar"}'),
        );
    }

    /**
     * @param string $string
     * @dataProvider dataProviderInvalidSPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS()
     * @test
     */
    public function SPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS_doesNotMatchInvalidSyntax($string)
    {
        $pattern = TemplateParser::$SPLIT_PATTERN_SHORTHANDSYNTAX_ARRAY_PARTS;
        $success = preg_match_all($pattern, $string, $matches, PREG_SET_ORDER) > 0;
        $this->assertFalse($success);
    }

    /**
     * Test the SCAN_PATTERN_CDATA which should detect <![CDATA[...]]> (with no leading or trailing spaces!)
     *
     * @test
     */
    public function testSCAN_PATTERN_CDATA()
    {
        $pattern = TemplateParser::$SCAN_PATTERN_CDATA;
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
     */
    protected function insertNamespaceIntoRegularExpression($regularExpression, $namespace)
    {
        return str_replace('NAMESPACE', implode('|', $namespace), $regularExpression);
    }
}

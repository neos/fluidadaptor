<?php
namespace TYPO3\Fluid\Tests\Unit\Core\Parser;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Testcase for ParsingState
 */
class ParsingStateTest extends \TYPO3\Flow\Tests\UnitTestCase
{
    /**
     * Parsing state
     *
     * @var \TYPO3\Fluid\Core\Parser\ParsingState
     */
    protected $parsingState;

    public function setUp()
    {
        $this->parsingState = new \TYPO3\Fluid\Core\Parser\ParsingState();
    }

    public function tearDown()
    {
        unset($this->parsingState);
    }

    /**
     * @test
     */
    public function setRootNodeCanBeReadOutAgain()
    {
        $rootNode = new \TYPO3\Fluid\Core\Parser\SyntaxTree\RootNode();
        $this->parsingState->setRootNode($rootNode);
        $this->assertSame($this->parsingState->getRootNode(), $rootNode, 'Root node could not be read out again.');
    }

    /**
     * @test
     */
    public function pushAndGetFromStackWorks()
    {
        $rootNode = new \TYPO3\Fluid\Core\Parser\SyntaxTree\RootNode();
        $this->parsingState->pushNodeToStack($rootNode);
        $this->assertSame($rootNode, $this->parsingState->getNodeFromStack(), 'Node returned from stack was not the right one.');
        $this->assertSame($rootNode, $this->parsingState->popNodeFromStack(), 'Node popped from stack was not the right one.');
    }

    /**
     * @test
     */
    public function renderCallsTheRightMethodsOnTheRootNode()
    {
        $renderingContext = $this->getMock(\TYPO3\Fluid\Core\Rendering\RenderingContextInterface::class);

        $rootNode = $this->getMock(\TYPO3\Fluid\Core\Parser\SyntaxTree\RootNode::class);
        $rootNode->expects($this->once())->method('evaluate')->with($renderingContext)->will($this->returnValue('T3DD09 Rock!'));
        $this->parsingState->setRootNode($rootNode);
        $renderedValue = $this->parsingState->render($renderingContext);
        $this->assertEquals($renderedValue, 'T3DD09 Rock!', 'The rendered value of the Root Node is not returned by the ParsingState.');
    }
}

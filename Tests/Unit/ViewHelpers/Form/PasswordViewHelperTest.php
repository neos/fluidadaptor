<?php
namespace TYPO3\Fluid\ViewHelpers\Form;

/*                                                                        *
 * This script belongs to the Flow framework.                             *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the MIT license.                                          *
 *                                                                        */

require_once(__DIR__ . '/Fixtures/EmptySyntaxTreeNode.php');
require_once(__DIR__ . '/Fixtures/Fixture_UserDomainClass.php');
require_once(__DIR__ . '/FormFieldViewHelperBaseTestcase.php');

/**
 * Test for the "Password" Form view helper
 */
class PasswordViewHelperTest extends \TYPO3\Fluid\Tests\Unit\ViewHelpers\Form\FormFieldViewHelperBaseTestcase
{
    /**
     * @var \TYPO3\Fluid\ViewHelpers\Form\TextboxViewHelper
     */
    protected $viewHelper;

    public function setUp()
    {
        parent::setUp();
        $this->viewHelper = $this->getAccessibleMock('TYPO3\Fluid\ViewHelpers\Form\PasswordViewHelper', array('setErrorClassAttribute', 'registerFieldNameForFormTokenGeneration'));
        $this->arguments['name'] = '';
        $this->injectDependenciesIntoViewHelper($this->viewHelper);
        $this->viewHelper->initializeArguments();
    }

    /**
     * @test
     */
    public function renderCorrectlySetsTagName()
    {
        $mockTagBuilder = $this->getMock('TYPO3\Fluid\Core\ViewHelper\TagBuilder', array('setTagName'), array(), '', false);
        $mockTagBuilder->expects($this->once())->method('setTagName')->with('input');
        $this->viewHelper->injectTagBuilder($mockTagBuilder);

        $this->viewHelper->initialize();
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderCorrectlySetsTypeNameAndValueAttributes()
    {
        $mockTagBuilder = $this->getMock('TYPO3\Fluid\Core\ViewHelper\TagBuilder', array('addAttribute', 'setContent', 'render'), array(), '', false);
        $mockTagBuilder->expects($this->at(0))->method('addAttribute')->with('type', 'password');
        $mockTagBuilder->expects($this->at(1))->method('addAttribute')->with('name', 'NameOfTextbox');
        $this->viewHelper->expects($this->once())->method('registerFieldNameForFormTokenGeneration')->with('NameOfTextbox');
        $mockTagBuilder->expects($this->at(2))->method('addAttribute')->with('value', 'Current value');
        $mockTagBuilder->expects($this->once())->method('render');
        $this->viewHelper->injectTagBuilder($mockTagBuilder);

        $arguments = array(
            'name' => 'NameOfTextbox',
            'value' => 'Current value'
        );
        $this->viewHelper->setArguments($arguments);

        $this->viewHelper->setViewHelperNode(new \TYPO3\Fluid\ViewHelpers\Fixtures\EmptySyntaxTreeNode());
        $this->viewHelper->initialize();
        $this->viewHelper->render();
    }

    /**
     * @test
     */
    public function renderCallsSetErrorClassAttribute()
    {
        $this->viewHelper->expects($this->once())->method('setErrorClassAttribute');
        $this->viewHelper->render();
    }
}

<?php
namespace TYPO3\Fluid\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Fluid".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ClassReflection;
use TYPO3\Fluid\Fluid;

/**
 * Common base class for XML generators.
 */
abstract class AbstractGenerator
{
    /**
     * The reflection class for AbstractViewHelper. Is needed quite often, that's why we use a pre-initialized one.
     *
     * @var ClassReflection
     */
    protected $abstractViewHelperReflectionClass;

    /**
     * The doc comment parser.
     *
     * @var \TYPO3\Flow\Reflection\DocCommentParser
     * @Flow\Inject
     */
    protected $docCommentParser;

    /**
     * @var \TYPO3\Flow\Reflection\ReflectionService
     * @Flow\Inject
     */
    protected $reflectionService;

    /**
     * Constructor. Sets $this->abstractViewHelperReflectionClass
     *
     */
    public function __construct()
    {
        Fluid::$debugMode = true; // We want ViewHelper argument documentation
        $this->abstractViewHelperReflectionClass = new ClassReflection('TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper');
    }

    /**
     * Get all class names inside this namespace and return them as array.
     *
     * @param string $namespace
     * @return array Array of all class names inside a given namespace.
     */
    protected function getClassNamesInNamespace($namespace)
    {
        $affectedViewHelperClassNames = array();

        $allViewHelperClassNames = $this->reflectionService->getAllSubClassNamesForClass('TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper');
        foreach ($allViewHelperClassNames as $viewHelperClassName) {
            if ($this->reflectionService->isClassAbstract($viewHelperClassName)) {
                continue;
            }
            if (strncmp($namespace, $viewHelperClassName, strlen($namespace)) === 0) {
                $affectedViewHelperClassNames[] = $viewHelperClassName;
            }
        }
        sort($affectedViewHelperClassNames);
        return $affectedViewHelperClassNames;
    }

    /**
     * Get a tag name for a given ViewHelper class.
     * Example: For the View Helper TYPO3\Fluid\ViewHelpers\Form\SelectViewHelper, and the
     * namespace prefix TYPO3\Fluid\ViewHelpers\, this method returns "form.select".
     *
     * @param string $className Class name
     * @param string $namespace Base namespace to use
     * @return string Tag name
     */
    protected function getTagNameForClass($className, $namespace)
    {
        $strippedClassName = substr($className, strlen($namespace));
        $classNameParts = explode('\\', $strippedClassName);

        if (count($classNameParts) == 1) {
            $tagName = lcfirst(substr($classNameParts[0], 0, -10)); // strip the "ViewHelper" ending
        } else {
            $tagName = lcfirst($classNameParts[0]) . '.' . lcfirst(substr($classNameParts[1], 0, -10));
        }
        return $tagName;
    }

    /**
     * Add a child node to $parentXmlNode, and wrap the contents inside a CDATA section.
     *
     * @param \SimpleXMLElement $parentXmlNode Parent XML Node to add the child to
     * @param string $childNodeName Name of the child node
     * @param string $childNodeValue Value of the child node. Will be placed inside CDATA.
     * @return \SimpleXMLElement the new element
     */
    protected function addChildWithCData(\SimpleXMLElement $parentXmlNode, $childNodeName, $childNodeValue)
    {
        $parentDomNode = dom_import_simplexml($parentXmlNode);
        $domDocument = new \DOMDocument();

        $childNode = $domDocument->appendChild($domDocument->createElement($childNodeName));
        $childNode->appendChild($domDocument->createCDATASection($childNodeValue));
        $childNodeTarget = $parentDomNode->ownerDocument->importNode($childNode, true);
        $parentDomNode->appendChild($childNodeTarget);
        return simplexml_import_dom($childNodeTarget);
    }
}

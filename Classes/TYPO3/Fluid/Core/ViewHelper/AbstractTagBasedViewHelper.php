<?php
namespace TYPO3\Fluid\Core\ViewHelper;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Fluid".                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Tag based view helper.
 * Sould be used as the base class for all view helpers which output simple tags, as it provides some
 * convenience methods to register default attributes, ...
 *
 * @api
 */
abstract class AbstractTagBasedViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Names of all registered tag attributes
	 *
	 * @var array
	 */
	static private $tagAttributes = array();

	/**
	 * Tag builder instance
	 *
	 * @var \TYPO3\Fluid\Core\ViewHelper\TagBuilder
	 * @api
	 */
	protected $tag = NULL;

	/**
	 * Name of the tag to be created by this view helper
	 *
	 * @var string
	 * @api
	 */
	protected $tagName = 'div';

	/**
	 * Inject a TagBuilder
	 *
	 * @param \TYPO3\Fluid\Core\ViewHelper\TagBuilder $tagBuilder Tag builder
	 * @return void
	 */
	public function injectTagBuilder(\TYPO3\Fluid\Core\ViewHelper\TagBuilder $tagBuilder) {
		$this->tag = $tagBuilder;
	}

	/**
	 * Constructor
	 *
	 * @api
	 */
	public function __construct() {
		$this->registerArgument('additionalAttributes', 'array', 'Additional tag attributes. They will be added directly to the resulting HTML tag.', FALSE);
	}

	/**
	 * Sets the tag name to $this->tagName.
	 * Additionally, sets all tag attributes which were registered in
	 * $this->tagAttributes and additionalArguments.
	 *
	 * Will be invoked just before the render method.
	 *
	 * @return void
	 * @api
	 */
	public function initialize() {
		parent::initialize();
		$this->tag->reset();
		$this->tag->setTagName($this->tagName);
		if ($this->hasArgument('additionalAttributes') && is_array($this->arguments['additionalAttributes'])) {
			$this->tag->addAttributes($this->arguments['additionalAttributes']);
		}

		if (isset(self::$tagAttributes[get_class($this)])) {
			foreach (self::$tagAttributes[get_class($this)] as $attributeName) {
				if ($this->hasArgument($attributeName) && $this->arguments[$attributeName] !== '') {
					$this->tag->addAttribute($attributeName, $this->arguments[$attributeName]);
				}
			}
		}
	}

	/**
	 * Register a new tag attribute. Tag attributes are all arguments which will be directly appended to a tag if you call $this->initializeTag()
	 *
	 * @param string $name Name of tag attribute
	 * @param string $type Type of the tag attribute
	 * @param string $description Description of tag attribute
	 * @param boolean $required set to TRUE if tag attribute is required. Defaults to FALSE.
	 * @return void
	 * @api
	 */
	protected function registerTagAttribute($name, $type, $description, $required = FALSE) {
		$this->registerArgument($name, $type, $description, $required, NULL);
		self::$tagAttributes[get_class($this)][$name] = $name;
	}

	/**
	 * Registers all standard HTML universal attributes.
	 * Should be used inside registerArguments();
	 *
	 * @return void
	 * @api
	 */
	protected function registerUniversalTagAttributes() {
		$this->registerTagAttribute('class', 'string', 'CSS class(es) for this element');
		$this->registerTagAttribute('dir', 'string', 'Text direction for this HTML element. Allowed strings: "ltr" (left to right), "rtl" (right to left)');
		$this->registerTagAttribute('id', 'string', 'Unique (in this file) identifier for this HTML element.');
		$this->registerTagAttribute('lang', 'string', 'Language for this element. Use short names specified in RFC 1766');
		$this->registerTagAttribute('style', 'string', 'Individual CSS styles for this element');
		$this->registerTagAttribute('title', 'string', 'Tooltip text of element');
		$this->registerTagAttribute('accesskey', 'string', 'Keyboard shortcut to access this element');
		$this->registerTagAttribute('tabindex', 'integer', 'Specifies the tab order of this element');
		$this->registerTagAttribute('onclick', 'string', 'JavaScript evaluated for the onclick event');
	}
}

?>
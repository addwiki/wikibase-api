<?php

namespace Wikibase\DataModel;

use Mediawiki\DataModel\Content;
use Wikibase\DataModel\Entity\Property;

/**
 * @author Addshore
 */
class PropertyContent extends Content {

	const MODEL = 'wikibase-property';

	/**
	 * @param Property $property
	 */
	public function __construct( Property $property ) {
		parent::__construct( $property, self::MODEL );
	}

	/**
	 * @see Content::getData
	 * @return Property
	 */
	public function getData() {
		return parent::getData();
	}

}
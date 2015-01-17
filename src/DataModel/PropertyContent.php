<?php

namespace Wikibase\DataModel;

use Mediawiki\DataModel\Content;
use Wikibase\DataModel\Entity\Property;

/**
 * @author Adam Shorland
 */
class PropertyContent extends Content {

	const MODEL = 'wikibase-property';

	/**
	 * @param Property $item
	 */
	public function __construct( Property $item ) {
		parent::__construct( $item, self::MODEL );
	}

	/**
	 * @see Content::getData
	 * @return Property
	 */
	public function getData() {
		return parent::getData();
	}

}
<?php

namespace Addwiki\Wikibase\DataModel;

use Addwiki\Mediawiki\DataModel\Content;
use Wikibase\DataModel\Entity\Property;

class PropertyContent extends Content {

	/**
	 * @var string
	 */
	public const MODEL = 'wikibase-property';

	public function __construct( Property $property ) {
		parent::__construct( $property, self::MODEL );
	}

	/**
	 * @required
	 * @see Content::getData
	 */
	public function getData(): Property {
		return parent::getData();
	}

}

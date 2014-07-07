<?php

namespace Wikibase\DataModel;

use Mediawiki\DataModel\Content;
use Wikibase\DataModel\Entity\Property;

class PropertyContent extends Content {

	const MODEL = 'wikibase-property';

	/**
	 * @var Property
	 */
	private $property;

	/**
	 * @param Property $property
	 */
	public function __construct( Property $property ) {
		$this->property = $property;
		parent::__construct( self::MODEL );
	}

	/**
	 * Returns a sha1 hash of the content
	 * @return string
	 */
	public function getHash() {
		return sha1( serialize( $this->property->toArray() ) );
	}

	/**
	 * @see Content::getNativeData
	 * @return Property
	 */
	public function getNativeData() {
		return $this->property;
	}
}
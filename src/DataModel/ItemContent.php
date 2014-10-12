<?php

namespace Wikibase\DataModel;

use Mediawiki\DataModel\Content;
use Wikibase\DataModel\Entity\Item;

class ItemContent extends Content {

	const MODEL = 'wikibase-item';

	/**
	 * @var Item
	 */
	private $item;

	/**
	 * @param Item $item
	 */
	public function __construct( Item $item ) {
		$this->item = $item;
		parent::__construct( self::MODEL );
	}

	/**
	 * Returns a sha1 hash of the content
	 * @return string
	 */
	public function getHash() {
		return sha1( serialize( $this->item ) );
	}

	/**
	 * @see Content::getNativeData
	 * @return Item
	 */
	public function getNativeData() {
		return $this->item;
	}
}